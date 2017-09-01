<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/PromptPay.php';

/**
 * @property KS\PromptPay PromptPay
 */
class PromptPayTest extends PHPUnit_Framework_TestCase {

  private $PromptPay;

  public function __construct() {
    $this->PromptPay = new KS\PromptPay();
  }

  public function testFormatTarget() {
    
    //Target phone number
    $target = '0899999999';
    $result = $this->PromptPay->formatTarget($target);
    $expected = '0066899999999';
    $this->assertEquals($expected, $result);
    
    $target = '089-999-9999';
    $result = $this->PromptPay->formatTarget($target);
    $expected = '0066899999999';
    $this->assertEquals($expected, $result);
    
    //Target ID
    $target = '1234567890123';
    $result = $this->PromptPay->formatTarget($target);
    $expected = '1234567890123';
    $this->assertEquals($expected, $result);
    
  }
  
  public function testformatAmount() {
    
    $amount = 1337.1337;
    $result = $this->PromptPay->formatAmount($amount);
    $expected = '1337.13';
    $this->assertEquals($expected, $result);
    
    $amount = 1337.1387;
    $result = $this->PromptPay->formatAmount($amount);
    $expected = '1337.14';
    $this->assertEquals($expected, $result);
  }
  
  public function testCrc16() {
    
    //https://www.blognone.com/node/95133
    $data = '00020101021129370016A000000677010111011300660000000005802TH53037646304';
    $result = $this->PromptPay->crc16($data);
    $expected = '8956';
    $this->assertEquals($expected, $result);
    
    $data = '00020101021129370016A000000677010111011300668999999995802TH53037646304';
    $result = $this->PromptPay->crc16($data);
    $expected = 'FE29';
    $this->assertEquals($expected, $result);
    
  }
  
  public function testGeneratePayload() {
    
    $target = '0891234567';
    $result = $this->PromptPay->generatePayload($target);
    
  }

}
