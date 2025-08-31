<?php

namespace App\Models;

use App\Media;
use App\Traits\BelongsToBusiness;
use App\Transaction;
use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model
};
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ZatcaInvoice extends Model
{
    use HasFactory, BelongsToBusiness;
    protected $guarded = [];
    public function responses()
    {
        return $this->morphMany(ZatcaResponse::class, 'responser');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
    public function zatca()
    {
        return $this->belongsTo(Zatca::class, 'zatca_id');
    }
    public function getXmlPathAttribute()
    {
        dd(
            'ERRRXXX21'
        );
        return asset('uploads/invoices_xml/' . $this->xml);
    }
    public function GetZatcaQrcodeAttribute()
    {
        $xmlMedia = !$this->relationLoaded('xml') ? $this->load('xml') : $this->xml;
        $xml = $xmlMedia->display_url;

        if ($xml) {
            $xml_string = $xml;
            $element = simplexml_load_string($xml_string);
            $element->registerXPathNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $element->registerXPathNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $result = $element->xpath('//cac:AdditionalDocumentReference[3]//cac:Attachment//cbc:EmbeddedDocumentBinaryObject')[0];
            return $result;
        }
    }

    /**
     * Get the xml associated with the ZatcaInvoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */

    public function xml(): MorphOne
    {
        return $this->morphOne(Media::class,'model');
    }
    /*
     *
     * Get QrCode value End
     *
     */

}
