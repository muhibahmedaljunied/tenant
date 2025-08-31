<?php
namespace App\Services\Zatca\Utils;

class QRCode
{
    private $result;

    public function __construct($params)
    {
        foreach ($params as $key => $value) {
            $tag = $key + 1;
            $length = $this->stringLen($value);
            $this->result .= $this->toString($tag, $length, $value);
        }
    }

    /**
     *  @return int number of bytes .
     */
    public function stringLen($string)
    {
        return strlen($string);
    }
    /**
     *  @param $tag 
     *  @param $length
     *  @param $value
     *  @return string returns a string representing the encoded TLV data structure . 
     */
    public function toString($tag, $length, $value)
    {
        return $this->__toHex($tag) . $this->__toHex($length) . ($value);
    }

    /**
     * to convert the string value to hex 
     *
     * @param $value
     *
     * @return false|string
     */
    public function __toHex($value)
    {

        return pack("H*", sprintf("%02X", $value));

    }

    public function getResult(): string
    {
        return $this->result;
    }
    /**
     * convert qr value to base64 encode
     * @return string qr code value represented in base64 encoding
     */
    public function toBase64()
    {
        return base64_encode($this->getResult());
    }

}