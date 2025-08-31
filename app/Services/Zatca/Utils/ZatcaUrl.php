<?php

namespace App\Services\Zatca\Utils;

class ZatcaUrl
{
   private static $developer = 'https://gw-fatoora.zatca.gov.sa/e-invoicing/developer-portal';
   private static $simulator = 'https://gw-apic-gov.gazt.gov.sa/e-invoicing/simulation';
   private static $production = 'https://gw-apic-gov.gazt.gov.sa/e-invoicing/core';

   public static function baseUrl()
   {
      return self::$developer;
   }
}
