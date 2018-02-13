<?php

namespace KS;

/**
 * Inspired and code logic from https://github.com/dtinth/promptpay-qr
 * More information https://www.blognone.com/node/95133
 */
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
  const BOT_ID_MERCHANT_EWALLET_ID = '03';
  const GUID_PROMPTPAY = 'A000000677010111';
  const TRANSACTION_CURRENCY_THB = '764';
  const COUNTRY_CODE_TH = 'TH';

  public function generatePayload($target, $amount = null) {

    $target = $this->sanitizeTarget($target);

    $targetType = strlen($target) >= 15 ? self::BOT_ID_MERCHANT_EWALLET_ID : (strlen($target) >= 13 ? self::BOT_ID_MERCHANT_TAX_ID : self::BOT_ID_MERCHANT_PHONE_NUMBER);

    $data = [
      $this->f(self::ID_PAYLOAD_FORMAT, self::PAYLOAD_FORMAT_EMV_QRCPS_MERCHANT_PRESENTED_MODE),
      $this->f(self::ID_POI_METHOD, $amount ? self::POI_METHOD_DYNAMIC : self::POI_METHOD_STATIC),
      $this->f(self::ID_MERCHANT_INFORMATION_BOT, $this->serialize([
          $this->f(self::MERCHANT_INFORMATION_TEMPLATE_ID_GUID, self::GUID_PROMPTPAY),
          $this->f($targetType, $this->formatTarget($target))
      ])),
      $this->f(self::ID_COUNTRY_CODE, self::COUNTRY_CODE_TH),
      $this->f(self::ID_TRANSACTION_CURRENCY, self::TRANSACTION_CURRENCY_THB),
    ];
    
    if ($amount !== null) {
      array_push($data, $this->f(self::ID_TRANSACTION_AMOUNT, $this->formatAmount($amount)));
    }
    
    $dataToCrc = $this->serialize($data) . self::ID_CRC . '04';
    array_push($data, $this->f(self::ID_CRC, $this->crc16($dataToCrc)));
    return $this->serialize($data);
  }

  public function f($id, $value) {
    return implode('', [$id, substr('00' . strlen($value), -2), $value]);
  }
  
  public function serialize($xs) {
    return implode('', $xs);
  }
  
  public function sanitizeTarget($str) {
    $str = preg_replace('/[^0-9]/', '', $str);
    return $str;
  }

  public function formatTarget($target) {
    
    $str = $this->sanitizeTarget($target);
    if (strlen($str) >= 13) {
      return $str;
    }
    
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
  
  public function generateQrCode($savePath, $target, $amount = null, $width = 500) {

    $payload = $this->generatePayload($target, $amount);

    $renderer = new \BaconQrCode\Renderer\Image\Png();
    $renderer->setHeight($width);
    $renderer->setWidth($width);
    $renderer->setMargin(0);
    $writer = new \BaconQrCode\Writer($renderer);
    $writer->writeFile($payload, $savePath);
  }

}
