<?php

namespace App\Utils;

use App\CashRegister;
use App\CashRegisterTransaction;
use App\Transaction;

use DB;

class CashRegisterUtil extends Util
{
    /**
     * Returns number of opened Cash Registers for the
     * current logged in user
     *
     * @return int
     */
    public function countOpenedRegister()
    {
        $user_id = auth()->user()->id;
        $count =  CashRegister::where('user_id', $user_id)
            ->where('status', 'open')
            ->count();
        return $count;
    }

    /**
     * Adds sell payments to currently opened cash register
     *
     * @param object/int $transaction
     * @param array $payments
     *
     * @return boolean
     */
    public function addSellPayments($transaction, $payments, $count_months = 0)
    {
        $user_id = auth()->user()->id;
        $register =  CashRegister::where('user_id', $user_id)
            ->where('status', 'open')
            ->first();
        $payments_formatted = [];
        foreach ($payments as $payment) {
            $payments_formatted[] = new CashRegisterTransaction([
                'amount' => (isset($payment['is_return']) && $payment['is_return'] == 1) ? (-1 * $this->num_uf($payment['amount'])) : $this->num_uf($payment['amount']),
                'pay_method' => $payment['method'],
                'type' => 'credit',
                'transaction_type' => 'sell',
                'transaction_id' => $transaction->id
            ]);
        }

        if (!empty($payments_formatted)) {
            $register->cash_register_transactions()->saveMany($payments_formatted);
        }

        return true;
    }

    /**
     * Adds sell payments to currently opened cash register
     *
     * @param object/int $transaction
     * @param array $payments
     *
     * @return boolean
     */
    public function updateSellPayments($status_before, $transaction, $payments)
    {
        $user_id = auth()->user()->id;
        $register =  CashRegister::where('user_id', $user_id)
            ->where('status', 'open')
            ->first();
        //If draft -> final then add all
        //If final -> draft then refund all
        //If final -> final then update payments
        if ($status_before == 'draft' && $transaction->status == 'final') {
            $this->addSellPayments($transaction, $payments);
        } elseif ($status_before == 'final' && $transaction->status == 'draft') {
            $this->refundSell($transaction);
        } elseif ($status_before == 'final' && $transaction->status == 'final') {
            $prev_payments = CashRegisterTransaction::where('transaction_id', $transaction->id)
                ->select(

                    DB::raw("SUM(CASE WHEN pay_method = 'cash' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_cash"),
                    DB::raw("SUM(CASE WHEN pay_method = 'card' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_card"),
                    DB::raw("SUM(CASE WHEN pay_method = 'cheque' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_cheque"),
                    DB::raw("SUM(CASE WHEN pay_method = 'bank_transfer' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_bank_transfer"),
                    DB::raw("SUM(CASE WHEN pay_method = 'other' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_other"),
                    DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_1' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_1"),
                    DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_2' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_2"),
                    DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_3' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_3"),
                    DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_4' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_4"),
                    DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_5' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_5"),
                    DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_6' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_6"),
                    DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_7' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_7"),
                    DB::raw("SUM(CASE WHEN pay_method = 'advance' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_advance")
                )->first();
            if (!empty($prev_payments)) {
                $payment_diffs = [
                    'cash' => $prev_payments->total_cash,
                    'card' => $prev_payments->total_card,
                    'cheque' => $prev_payments->total_cheque,
                    'bank_transfer' => $prev_payments->total_bank_transfer,
                    'other' => $prev_payments->total_other,
                    'custom_pay_1' => $prev_payments->total_custom_pay_1,
                    'custom_pay_2' => $prev_payments->total_custom_pay_2,
                    'custom_pay_3' => $prev_payments->total_custom_pay_3,
                    'custom_pay_4' => $prev_payments->total_custom_pay_4,
                    'custom_pay_5' => $prev_payments->total_custom_pay_5,
                    'custom_pay_6' => $prev_payments->total_custom_pay_6,
                    'custom_pay_7' => $prev_payments->total_custom_pay_7,
                    'advance' => $prev_payments->total_advance
                ];

                foreach ($payments as $payment) {
                    if (isset($payment['is_return']) && $payment['is_return'] == 1) {
                        $payment_diffs[$payment['method']] += $this->num_uf($payment['amount']);
                    } else {
                        $payment_diffs[$payment['method']] -= $this->num_uf($payment['amount']);
                    }
                }
                $payments_formatted = [];
                foreach ($payment_diffs as $key => $value) {
                    if ($value > 0) {
                        $payments_formatted[] = new CashRegisterTransaction([
                            'amount' => $value,
                            'pay_method' => $key,
                            'type' => 'debit',
                            'transaction_type' => 'refund',
                            'transaction_id' => $transaction->id
                        ]);
                    } elseif ($value < 0) {
                        $payments_formatted[] = new CashRegisterTransaction([
                            'amount' => -1 * $value,
                            'pay_method' => $key,
                            'type' => 'credit',
                            'transaction_type' => 'sell',
                            'transaction_id' => $transaction->id
                        ]);
                    }
                }
                if (!empty($payments_formatted)) {
                    $register->cash_register_transactions()->saveMany($payments_formatted);
                }
            }
        }

        return true;
    }

    /**
     * Refunds all payments of a sell
     *
     * @param object/int $transaction
     *
     * @return boolean
     */
    public function refundSell($transaction)
    {
        $user_id = auth()->user()->id;
        $register =  CashRegister::where('user_id', $user_id)
            ->where('status', 'open')
            ->first();

        $total_payment = CashRegisterTransaction::where('transaction_id', $transaction->id)
            ->select(

                DB::raw("SUM(CASE WHEN pay_method = 'cash' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_cash"),
                DB::raw("SUM(CASE WHEN pay_method = 'card' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_card"),
                DB::raw("SUM(CASE WHEN pay_method = 'cheque' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_cheque"),
                DB::raw("SUM(CASE WHEN pay_method = 'bank_transfer' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_bank_transfer"),
                DB::raw("SUM(CASE WHEN pay_method = 'other' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_other"),
                DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_1' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_1"),
                DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_2' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_2"),
                DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_3' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_3"),
                DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_4' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_4"),
                DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_5' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_5"),
                DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_6' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_6"),
                DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_7' THEN CASE WHEN type = 'credit' THEN amount ELSE -1 * amount END ELSE 0 END) as total_custom_pay_7")
            )->first();
        $refunds = [
            'cash' => $total_payment->total_cash,
            'card' => $total_payment->total_card,
            'cheque' => $total_payment->total_cheque,
            'bank_transfer' => $total_payment->total_bank_transfer,
            'other' => $total_payment->total_other,
            'custom_pay_1' => $total_payment->total_custom_pay_1,
            'custom_pay_2' => $total_payment->total_custom_pay_2,
            'custom_pay_3' => $total_payment->total_custom_pay_3,
            'custom_pay_4' => $total_payment->total_custom_pay_4,
            'custom_pay_5' => $total_payment->total_custom_pay_5,
            'custom_pay_6' => $total_payment->total_custom_pay_6,
            'custom_pay_7' => $total_payment->total_custom_pay_7
        ];
        $refund_formatted = [];
        foreach ($refunds as $key => $val) {
            if ($val > 0) {
                $refund_formatted[] = new CashRegisterTransaction([
                    'amount' => $val,
                    'pay_method' => $key,
                    'type' => 'debit',
                    'transaction_type' => 'refund',
                    'transaction_id' => $transaction->id
                ]);
            }
        }

        if (!empty($refund_formatted)) {
            $register->cash_register_transactions()->saveMany($refund_formatted);
        }
        return true;
    }

    /**
     * Retrieves details of given rigister id else currently opened register
     *
     * @param $register_id default null
     *
     * @return object
     */
    public function getRegisterDetails($register_id = null)
    {
        $query = CashRegister::leftjoin(
            'cash_register_transactions as ct',
            'ct.cash_register_id',
            '=',
            'cash_registers.id'
        )
            ->join(
                'users as u',
                'u.id',
                '=',
                'cash_registers.user_id'
            )
            ->leftJoin(
                'business_locations as bl',
                'bl.id',
                '=',
                'cash_registers.location_id'
            );
        if (empty($register_id)) {
            $user_id = auth()->user()->id;
            $query->where('user_id', $user_id)
                ->where('cash_registers.status', 'open');
        } else {
            $query->where('cash_registers.id', $register_id);
        }

        $register_details = $query->select(
            'cash_registers.created_at as open_time',
            'cash_registers.closed_at as closed_at',
            'cash_registers.user_id',
            'cash_registers.closing_note',
            'cash_registers.location_id',
            DB::raw("SUM(CASE WHEN transaction_type = 'initial' THEN amount ELSE 0 END) as cash_in_hand"),
            DB::raw("SUM(CASE WHEN transaction_type = 'sell' THEN amount WHEN transaction_type = 'refund' THEN -1 * amount ELSE 0 END) as total_sale"),
            DB::raw("SUM(CASE WHEN pay_method = 'cash' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_cash"),
            DB::raw("SUM(CASE WHEN pay_method = 'cheque' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_cheque"),
            DB::raw("SUM(CASE WHEN pay_method = 'card' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_card"),
            DB::raw("SUM(CASE WHEN pay_method = 'bank_transfer' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_bank_transfer"),
            DB::raw("SUM(CASE WHEN pay_method = 'other' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_other"),
            DB::raw("SUM(CASE WHEN pay_method = 'advance' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_advance"),
            DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_1' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_custom_pay_1"),
            DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_2' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_custom_pay_2"),
            DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_3' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_custom_pay_3"),
            DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_4' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_custom_pay_4"),
            DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_5' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_custom_pay_5"),
            DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_6' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_custom_pay_6"),
            DB::raw("SUM(CASE WHEN pay_method = 'custom_pay_7' AND transaction_type = 'sell' THEN amount ELSE 0 END) as total_custom_pay_7"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' THEN amount ELSE 0 END) as total_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'cash' THEN amount ELSE 0 END) as total_cash_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'cheque' THEN amount ELSE 0 END) as total_cheque_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'card' THEN amount ELSE 0 END) as total_card_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'bank_transfer' THEN amount ELSE 0 END) as total_bank_transfer_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'other' THEN amount ELSE 0 END) as total_other_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'advance' THEN amount ELSE 0 END) as total_advance_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'custom_pay_1' THEN amount ELSE 0 END) as total_custom_pay_1_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'custom_pay_2' THEN amount ELSE 0 END) as total_custom_pay_2_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'custom_pay_3' THEN amount ELSE 0 END) as total_custom_pay_3_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'custom_pay_4' THEN amount ELSE 0 END) as total_custom_pay_4_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'custom_pay_5' THEN amount ELSE 0 END) as total_custom_pay_5_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'custom_pay_6' THEN amount ELSE 0 END) as total_custom_pay_6_refund"),
            DB::raw("SUM(CASE WHEN transaction_type = 'refund' AND pay_method = 'custom_pay_7' THEN amount ELSE 0 END) as total_custom_pay_7_refund"),
            DB::raw("SUM(CASE WHEN pay_method = 'cheque' THEN 1 ELSE 0 END) as total_cheques"),
            DB::raw("SUM(CASE WHEN pay_method = 'card' THEN 1 ELSE 0 END) as total_card_slips"),
            DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as user_name"),
            'u.email',
            'bl.name as location_name'

        )->groupBy(
            'cash_registers.created_at',
            'cash_registers.closed_at',
            'cash_registers.user_id',
            'cash_registers.closing_note',
            'cash_registers.location_id',
            'u.surname',
            'u.first_name',
            'u.last_name',
            'u.email',
            'bl.name'
        )

            ->first();

        return $register_details;
    }

    /**
     * Get the transaction details for a particular register
     *
     * @param $user_id int
     * @param $open_time datetime
     * @param $close_time datetime
     *
     * @return array
     */
    public function getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled = false)
    {
        $product_details = Transaction::where('transactions.created_by', $user_id)
            ->whereBetween('transaction_date', [$open_time, $close_time])
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final')
            ->where('transactions.is_direct_sale', 0)
            ->join('transaction_sell_lines AS TSL', 'transactions.id', '=', 'TSL.transaction_id')
            ->join('products AS P', 'TSL.product_id', '=', 'P.id')
            ->leftjoin('brands AS B', 'P.brand_id', '=', 'B.id')
            ->groupBy('B.id', 'B.name')
            ->select(
                'B.name as brand_name',
                DB::raw('SUM(TSL.quantity) as total_quantity'),
                DB::raw('SUM(TSL.unit_price_inc_tax*TSL.quantity) as total_amount')
            )
            // ->orderByRaw('CASE WHEN brand_name IS NULL THEN 2 ELSE 1 END, brand_name')
            ->orderByRaw("CASE WHEN B.name IS NULL THEN 2 ELSE 1 END, B.name")
            ->get();

        //If types of service
        $types_of_service_details = null;
        if ($is_types_of_service_enabled) {
            $types_of_service_details = Transaction::where('transactions.created_by', $user_id)
                ->whereBetween('transaction_date', [$open_time, $close_time])
                ->where('transactions.is_direct_sale', 0)
                ->where('transactions.type', 'sell')
                ->where('transactions.status', 'final')
                ->leftjoin('types_of_services AS tos', 'tos.id', '=', 'transactions.types_of_service_id')
                ->groupBy('tos.id','tos.id', 'tos.name')
                ->select(
                    'tos.name as types_of_service_name',
                    DB::raw('SUM(final_total) as total_sales')
                )
                ->orderBy('total_sales', 'desc')
                ->get();
        }

        $transaction_details = Transaction::where('transactions.created_by', $user_id)
            ->whereBetween('transaction_date', [$open_time, $close_time])
            ->where('transactions.type', 'sell')
            ->where('transactions.is_direct_sale', 0)
            ->where('transactions.status', 'final')
            ->select(
                DB::raw('SUM(tax_amount) as total_tax'),
                DB::raw("SUM(CASE WHEN discount_type = 'percentage' THEN total_before_tax * discount_amount / 100 ELSE discount_amount END) as total_discount"),
                DB::raw('SUM(final_total) as total_sales')
            )
            ->first();

        return [
            'product_details' => $product_details,
            'transaction_details' => $transaction_details,
            'types_of_service_details' => $types_of_service_details
        ];
    }

    /**
     * Retrieves the currently opened cash register for the user
     *
     * @param $int user_id
     *
     * @return obj
     */
    public function getCurrentCashRegister($user_id)
    {
        $register =  CashRegister::where('user_id', $user_id)
            ->where('status', 'open')
            ->first();

        return $register;
    }
}
