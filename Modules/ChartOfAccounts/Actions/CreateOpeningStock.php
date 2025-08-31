<?php

namespace Modules\ChartOfAccounts\Actions;

use Exception;
use App\Product;
use App\{
    Transaction,
    BusinessLocation,
    PurchaseLine
};
use App\Utils\{ProductUtil, TransactionUtil};
use Illuminate\Support\Facades\DB;

class CreateOpeningStock
{
    protected $productUtil;
    protected $transactionUtil;

    public function __construct(ProductUtil $productUtil, TransactionUtil $transactionUtil)
    {
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
    }

    public function create($product_id, $location_id, $price, $qty)
    {
        try {
            $business_id = request()->session()->get('user.business_id');
            $user_id = request()->session()->get('user.id');

            $product = Product::where('business_id', $business_id)
                ->where('id', $product_id)
                ->with(['variations', 'product_tax'])
                ->first();

            $locations = BusinessLocation::forDropdown($business_id)->toArray();

            if (! empty($product) && $product->enable_stock == 1) {
                //Get product tax
                $tax_percent = ! empty($product->product_tax->amount) ? $product->product_tax->amount : 0;
                $tax_id = ! empty($product->product_tax->id) ? $product->product_tax->id : null;

                //Get start date for financial year.
                $transaction_date = request()->session()->get("financial_year.start");
                $transaction_date = \Carbon::createFromFormat('Y-m-d', $transaction_date)->toDateTimeString();

                DB::beginTransaction();
                //$key is the location_id
                $purchase_total = 0;
                //Check if valid location
                if (array_key_exists($location_id, $locations)) {
                    $purchase_lines = [];
                    $updated_purchase_line_ids = [];

                    //create purchase_lines array
                    //$k is the variation id
                    $purchase_price = $this->productUtil->num_uf(trim($price));
                    $item_tax = $this->productUtil->calc_percentage($purchase_price, $tax_percent);
                    $purchase_price_inc_tax = $purchase_price + $item_tax;
                    $qty_remaining = $this->productUtil->num_uf(trim($qty));


                    $purchase_line = null;


                    if ($qty_remaining != 0) {

                        $k = $product->variations()->first()->id;
                        //create newly added purchase lines
                        $purchase_line = new PurchaseLine();
                        $purchase_line->product_id = $product->id;
                        $purchase_line->variation_id = $k;
                        $this->productUtil->updateProductQuantity($location_id, $product->id, $k, $qty_remaining, 0, null, false);

                        //Calculate transaction total
                        $purchase_total += ($purchase_price_inc_tax * $qty_remaining);
                    }
                    if (! is_null($purchase_line)) {
                        $purchase_line->item_tax = $item_tax;
                        $purchase_line->tax_id = $tax_id;
                        $purchase_line->quantity = $qty_remaining;
                        $purchase_line->pp_without_discount = $purchase_price;
                        $purchase_line->purchase_price = $purchase_price;
                        $purchase_line->purchase_price_inc_tax = $purchase_price_inc_tax;

                        $purchase_lines[] = $purchase_line;
                    }

                    //create transaction & purchase lines
                    if (! empty($purchase_lines)) {
                        $is_new_transaction = false;

                        $transaction = Transaction::where('type', 'opening_stock')
                            ->where('business_id', $business_id)
                            ->where('opening_stock_product_id', $product->id)
                            ->where('location_id', $location_id)
                            ->first();
                        if (! empty($transaction)) {
                            $transaction->total_before_tax = $purchase_total;
                            $transaction->final_total = $purchase_total;
                            $transaction->update();
                        } else {
                            $is_new_transaction = true;

                            $transaction = Transaction::create(
                                [
                                    'type' => 'opening_stock',
                                    'opening_stock_product_id' => $product->id,
                                    'status' => 'received',
                                    'business_id' => $business_id,
                                    'transaction_date' => $transaction_date,
                                    'total_before_tax' => $purchase_total,
                                    'location_id' => $location_id,
                                    'final_total' => $purchase_total,
                                    'payment_status' => 'paid',
                                    'created_by' => $user_id
                                ]
                            );
                        }

                        //unset deleted purchase lines
                        $delete_purchase_line_ids = [];
                        $delete_purchase_lines = null;
                        $delete_purchase_lines = PurchaseLine::where('transaction_id', $transaction->id)
                            ->whereNotIn('id', $updated_purchase_line_ids)
                            ->get();

                        if ($delete_purchase_lines->count()) {
                            foreach ($delete_purchase_lines as $delete_purchase_line) {
                                $delete_purchase_line_ids[] = $delete_purchase_line->id;

                                //decrease deleted only if previous status was received
                                $this->productUtil->decreaseProductQuantity(
                                    $delete_purchase_line->product_id,
                                    $delete_purchase_line->variation_id,
                                    $transaction->location_id,
                                    $delete_purchase_line->quantity
                                );
                            }
                            //Delete deleted purchase lines
                            PurchaseLine::where('transaction_id', $transaction->id)
                                ->whereIn('id', $delete_purchase_line_ids)
                                ->delete();
                        }
                        $transaction->purchase_lines()->saveMany($purchase_lines);

                        //Update mapping of purchase & Sell.
                        if (! $is_new_transaction) {
                            $this->transactionUtil->adjustMappingPurchaseSellAfterEditingPurchase('received', $transaction, $delete_purchase_lines);
                        }

                        //Adjust stock over selling if found
                        $this->productUtil->adjustStockOverSelling($transaction);
                    } else {
                        //Delete transaction if all purchase line quantity is 0 (Only if transaction exists)
                        $delete_transaction = Transaction::where('type', 'opening_stock')
                            ->where('business_id', $business_id)
                            ->where('opening_stock_product_id', $product->id)
                            ->where('location_id', $location_id)
                            ->with(['purchase_lines'])
                            ->first();

                        if (! empty($delete_transaction)) {
                            $delete_purchase_lines = $delete_transaction->purchase_lines;

                            foreach ($delete_purchase_lines as $delete_purchase_line) {
                                $this->productUtil->decreaseProductQuantity($product->id, $delete_purchase_line->variation_id, $location_id, $delete_purchase_line->quantity);
                                $delete_purchase_line->delete();
                            }

                            //Update mapping of purchase & Sell.
                            $this->transactionUtil->adjustMappingPurchaseSellAfterEditingPurchase('received', $delete_transaction, $delete_purchase_lines);

                            $delete_transaction->delete();
                        }
                    }
                }

                DB::commit();
            }

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}\n Trace: {$e->getTraceAsString()}");

            throw $e;
        }
    }
}
