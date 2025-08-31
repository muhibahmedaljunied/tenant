<?php
namespace App\Services\Zatca\InvoiceBuilder;

class BuildInvoiceLines {

    public $invoice;
    public $items_total = 0;
    // taxes variables start .
    public $lines_tax_total = 0;
    public $generated_tax_lines = '';
    // taxes variables end .
    // lines sub total variables start .
    public $lines_discount_total = 0;
    public $lines_sub_total = 0;
    // lines sub total variables end .
    public $lines_net_total = 0;
    public $generated_invoice_allowance_charge = '';
    public $generated_lines_xml = '';

    public function __construct($invoice_obj){
        $this->invoice = $invoice_obj;
        foreach($this->invoice->items??[] as $key=>$line){
            $this->lines_discount_total +=$this->GenerateLineDiscountTotal($line);
            $this->GenerateLineDiscounts($line);
            $this->GenerateLineXml($line,$key);
            $this->items_total += $this->GenerateLineItemsTotal($line,$key);
        }
        foreach($this->getUniqueTaxCategories()??[] as $unique_tax){
            $xml_tax_line = file_get_contents(public_path('zatca/xml/xml_tax_line.xml'));
            $xml_tax_line = str_replace("ITEM_SUB_TOTAL",number_format($unique_tax['taxable_amount'],2,'.',''),$xml_tax_line);
            $xml_tax_line = str_replace("ITEM_TOTAL_TAX",number_format($unique_tax['tax_amount'],2,'.',''),$xml_tax_line);
            $xml_tax_line = str_replace("SET_TAX_VALUE",number_format($unique_tax['percentage'],2,'.',''),$xml_tax_line);
            $xml_tax_line = str_replace("SET_TAX_CATEGORY",$unique_tax['category'],$xml_tax_line);
            if($unique_tax['category'] != 'S'){
                $xml_for_zero_tax = file_get_contents(public_path('zatca/xml/xml_for_zero_tax.xml'));
                $xml_for_zero_tax = str_replace("SET_TYPE",$unique_tax['type'],$xml_for_zero_tax);
                $xml_for_zero_tax = str_replace("SET_REASON",$unique_tax['reason'],$xml_for_zero_tax);
            }else{
                $xml_for_zero_tax = "";
            }
            $xml_tax_line = str_replace("SET_ZERO_TAX_AND_REASON",$xml_for_zero_tax,$xml_tax_line);
            $xml_tax_line = str_replace("CURRENCY_NAME",$this->invoice->currency,$xml_tax_line);
            $this->generated_tax_lines .= $xml_tax_line;
        }
        $this->GenerateExtenstionTaxesXml();
    }
    public function getUniqueTaxCategories(){
        $unique_taxes = [];
        foreach($this->invoice->items as $item){
           foreach($item->taxes as $tax){
                $taxable_amount = $this->GenerateLineSubTotal($item);
                $tax_amount = $taxable_amount * $tax->percentage / 100;
                if(count($unique_taxes) == 0){
                    $unique_taxes[] = ['percentage' => $tax->percentage, 'category' => $tax->category , 'type' => $tax->type, 'reason' => $tax->reason, 'taxable_amount' => $taxable_amount , 'tax_amount' => $tax_amount];
                }else{
                    $check_exists = false;
                    foreach($unique_taxes??[] as $key=>$unique_tax){
                        if($unique_tax['category'] == $tax->category){
                            $check_exists = $key;
                            break;
                        }
                    }
                    if($check_exists === false){
                        $unique_taxes[] = ['percentage' => $tax->percentage, 'category' => $tax->category,'type' => $tax->type, 'reason' => $tax->reason, 'taxable_amount' => $taxable_amount , 'tax_amount' => $tax_amount];
                    }else{
                        $unique_taxes[$check_exists]['taxable_amount'] = $unique_taxes[$check_exists]['taxable_amount'] + $taxable_amount;
                        $unique_taxes[$check_exists]['tax_amount'] = $unique_taxes[$check_exists]['tax_amount'] + $tax_amount;
                    }
                }
           }
        }
        return $unique_taxes;
    }
    /**
     * 
     * Generate All Performed Discount For Single Line Start .
     * 
    */
    public function GenerateLineDiscounts($line){
        $line_discounts = '';
        foreach($line->discounts??[] as $key=>$discount){
            $discount_amount = number_format($discount->amount,2,'.','');
            $xml_line_item_discount = file_get_contents(public_path('zatca/xml/xml_line_item_discount.xml'));
            $line_discount_single = str_replace("DISCOUNT_REASON",$discount->reason,$xml_line_item_discount);
            $line_discount_single = str_replace("DISCOUNT_VALUE",$discount_amount,$line_discount_single);
            $line_discount_single = str_replace("CURRENCY_NAME",$this->invoice->currency,$line_discount_single);
            $line_discounts .= $line_discount_single.(($key != count($line->discounts)-1) ? "\n" : "");
        }
        return $line_discounts;
    }
    /**
     * 
     * Generate All Performed Discount For Single Line End .
     * 
    */

    /**
     * 
     * Generate All Performed Taxes Xml For Single Line Start .
     * 
    */
    public function GenerateExtenstionTaxesXml(){
        foreach($this->getUniqueTaxCategories()??[] as $key=>$unique_tax){
            $net_total = $unique_tax['tax_amount'] + $unique_tax['taxable_amount'];
            $this->lines_tax_total += $unique_tax['tax_amount'];
            $this->lines_sub_total += $unique_tax['taxable_amount'];
            $this->lines_net_total += $net_total;
            $allowance_value = $this->lines_discount_total / count($this->getUniqueTaxCategories());
            $xml_allowance_line = file_get_contents(public_path('zatca/xml/xml_invoice_allowance_lines.xml'));
            $xml_allowance_line = str_replace("ALLOWANCE_VALUE",$allowance_value,$xml_allowance_line);
            $xml_allowance_line = str_replace("ALLOWANCE_INDEX",$key+1,$xml_allowance_line);
            $xml_allowance_line = str_replace("PERCENT_VALUE",$unique_tax['percentage'],$xml_allowance_line);
            $xml_allowance_line = str_replace("CATEGORY",$unique_tax['category'],$xml_allowance_line);
            $xml_allowance_line = str_replace("CURRENCY_NAME",$this->invoice->currency,$xml_allowance_line);
            $this->generated_invoice_allowance_charge .= $xml_allowance_line;
        }
    }
    /**
     * 
     * Generate All Performed Taxes Xml For Single Line End .
     * 
    */

    /**
     * 
     * Generate All Performed Taxes For Single Line Start .
     * 
    */
    public function GenerateLineTaxes($line){
        $line_Taxes_total = 0;
        foreach($line->taxes??[] as $key=>$tax){
            $item_sub_total = $this->GenerateLineItemsTotal($line);
            $line_Taxes_total += $item_sub_total * $tax->percentage / 100;
        }
        return $line_Taxes_total;
    }
    /**
     * 
     * Generate All Performed Taxes For Single Line End .
     * 
    */

    /**
     * 
     * Generate All Performed Taxes Categories For Single Line Start .
     * 
    */
    public function GenerateLineTaxesCategories($line){
        $line_tax_category = '';
        foreach($line->taxes??[] as $key=>$tax){
            $tax_percent = number_format($tax->percentage,2,'.','');
            $xml_line_item_tax_category = file_get_contents(public_path('zatca/xml/xml_line_item_tax_category.xml'));
            $line_tax_category_single = str_replace("PERCENT_VALUE",$tax_percent,$xml_line_item_tax_category);
            $line_tax_category_single = str_replace("CATEGORY",$tax->category,$line_tax_category_single);
            $line_tax_category .= $line_tax_category_single.(($key != count($line->taxes)-1) ? "\n" : "");
        }
        return $line_tax_category;
    }
    /**
     * 
     * Generate All Performed Taxes Categories For Single Line End .
     * 
    */

    /**
     * 
     * Generate  Xml Tax Totals and Tax Lines Start .
     * 
    */
    public function GenerateTaxTotalsXml(){

        $tax_totals_xml = file_get_contents(public_path('zatca/xml/xml_tax_totals.xml'));
        $tax_total_current_currency_total = $this->lines_tax_total;
        $tax_total_current_currency = $this->invoice->currency;
        if($this->invoice->currency == 'USD'){
            $tax_total_sar = $this->lines_tax_total * 3.75;
            $tax_total_current_currency = 'SAR';
        }else{
            $tax_total_sar = $this->lines_tax_total;
        }
        $tax_lines = str_replace("SET_LINES_TAX_TOTAL_PARENT",number_format($tax_total_sar,2,'.',''),$tax_totals_xml);
        $tax_lines = str_replace("SET_CURRENCY_NAME_PARENT",$tax_total_current_currency,$tax_lines);
        $tax_lines = str_replace("SET_LINES_TAX_TOTAL",number_format($tax_total_current_currency_total,2,'.',''),$tax_lines);
        $tax_lines = str_replace("SET_TAX_LINES",$this->generated_tax_lines,$tax_lines);
        $tax_lines = str_replace("CURRENCY_NAME",$this->invoice->currency,$tax_lines);
        return $tax_lines;
    }
    /**
     * 
     * Generate  Xml Tax Totals and Tax Lines End .
     * 
    */

    /**
     * 
     * Generate Line Sub Total Start .
     * 
    */
    public function GenerateLineItemsTotal($line){

        return $line->qty * $line->sell_price;
    }
    /**
     * 
     * Generate Line Sub Total End .
     * 
    */

    /**
     * 
     * Generate Line Sub Total Start .
     * 
    */
    public function GenerateLineSubTotal($line){

        return ($line->qty * $line->sell_price) - $this->GenerateLineDiscountTotal($line);
    }
    /**
     * 
     * Generate Line Sub Total End .
     * 
    */

    /**
     * 
     * Generate Line Total Include Taxes Start .
     * 
    */
    public function GenerateLineTotalIncludeTaxes($line){

        return $this->GenerateLineItemsTotal($line) + $this->GenerateLineTaxes($line);
    }
    /**
     * 
     * Generate Line Total Include Taxes End .
     * 
    */

    /**
     * 
     * Generate Line Discount Total Start .
     * 
    */
    public function GenerateLineDiscountTotal($line){
        $discount_total = 0;
        foreach($line->discounts??[] as $key=>$discount){
            //$discount_total += $discount->amount;
            $discount_total += $discount->amount;
        }
        return $discount_total;
    }
    /**
     * 
     * Generate Line Discount Total End .
     * 
    */

    /**
     * 
     * Generate Line Xml Start .
     * 
    */
    public function GenerateLineXml($line,$key){
        $xml_line_item = file_get_contents(public_path('zatca/xml/xml_line_item.xml'));
        $line_xml = str_replace("ITEM_ID",$line->id,$xml_line_item);
        $line_xml = str_replace("ITEM_QTY",$line->qty,$line_xml);
        $line_xml = str_replace("ITEM_PRICE",$line->sell_price,$line_xml);
        $line_xml = str_replace("ITEM_NAME",$line->name,$line_xml);
        $line_xml = str_replace("ITEM_TAX_CATEGORY",$this->GenerateLineTaxesCategories($line),$line_xml);
        $line_xml = str_replace("ITEM_SUB_TOTAL",number_format($this->GenerateLineItemsTotal($line),2,'.',''),$line_xml);
        $line_xml = str_replace("ITEM_TOTAL_TAX",number_format($this->GenerateLineTaxes($line),2,'.',''),$line_xml);
        $line_xml = str_replace("ITEM_TOTAL_INCLUDE_TAX",number_format($this->GenerateLineTotalIncludeTaxes($line),2,'.',''),$line_xml);
        $line_xml = str_replace("ITEM_DISCOUNT",$this->GenerateLineDiscounts($line),$line_xml);
        $line_xml = str_replace("CURRENCY_NAME",$this->invoice->currency,$line_xml);
        $this->generated_lines_xml .= $line_xml.(($key != count($this->invoice->items)-1) ? "\n" : "");
    }
    /**
     * 
     * Generate Line Xml End .
     * 
    */

}
