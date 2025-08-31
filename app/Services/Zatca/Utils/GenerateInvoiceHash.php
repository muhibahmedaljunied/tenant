<?php
namespace App\Services\Zatca\Utils;

class GenerateInvoiceHash
{
    private $xml;

    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    /**
     * Generate Invoice Binary Hash .
     * @return string
     */
    public function GenerateBinaryHash(): string
    {
        return hash('sha256', $this->xml, true);
    }


    /**
     * Generate Invoice Binary Hash Encoded in Base64 .
     * @return string
     */
    public function GenerateBinaryHashEncoded(): string
    {
        return base64_encode($this->GenerateBinaryHash());
    }

}
