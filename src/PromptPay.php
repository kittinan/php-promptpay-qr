<?php

namespace KS;

class PromptPay {

  const ID_PAYLOAD_FORMAT = '00';
  const ID_POI_METHOD = '01';
  const ID_MERCHANT_INFORMATION_BOT = '29';
  const ID_TRANSACTION_CURRENCY = '53';
  const ID_TRANSACTION_AMOUNT = '54';
  const ID_COUNTRY_CODE = '58';
  const ID_CRC = '63';
  
  const PAYLOAD_FORMAT_EMV_QRCPS_MERCHANT_PRESENTED_MODE = '01';
  const POI_METHOD_STATIC = '11';
  const POI_METHOD_DYNAMIC = '12';
  const MERCHANT_INFORMATION_TEMPLATE_ID_GUID = '00';
  const BOT_ID_MERCHANT_PHONE_NUMBER = '01';
  const BOT_ID_MERCHANT_TAX_ID = '02';
  const GUID_PROMPTPAY = 'A000000677010111';
  const TRANSACTION_CURRENCY_THB = '764';
  const COUNTRY_CODE_TH = 'TH';

  public function generatePayload($target, $amount = null) {

    $targetType = strlen($target) >= 13 ? self::BOT_ID_MERCHANT_TAX_ID : self::BOT_ID_MERCHANT_PHONE_NUMBER;
  }

  public function f($id, $value) {
    return implode('', [$id, substr('00' + strlen($value), -2), $value]);
  }

  public function formatTarget($target) {

    $str = str_replace('-', '', $target);
    $str = preg_replace('/^0/', '66', $str);
    $str = '0000000000000' . $str;

    return substr($str, -13);
  }

  public function formatAmount($amount) {
    return number_format($amount, 2, '.', '');
  }

  public function crc16($data) {
    $crc16 = new \mermshaus\CRC\CRC16CCITT();
    $crc16->update($data);
    $checksum = $crc16->finish();
    return strtoupper(bin2hex($checksum));
  }

}
