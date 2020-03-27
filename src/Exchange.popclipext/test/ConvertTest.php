<?php
require('../convert.php');

use PHPUnit\Framework\TestCase;

class ConvertTest extends TestCase {
  static $test_json = <<<EOL
{"quotes":[
{"currencyPairCode":"USDJPY","ask":"1.1"},
{"currencyPairCode":"EURJPY","ask":"1.2"},
{"currencyPairCode":"GBPJPY","ask":"1.3"},
{"currencyPairCode":"AUDJPY","ask":"1.4"},
{"currencyPairCode":"CADJPY","ask":"1.5"}
]}
EOL;
  protected $converter;

  protected function setUp(): void
  {
    $this->converter = new Converter(self::$test_json);
  }
    
  public function testCovertDollarToYen() {
    $this->assertSame('¥110', $this->converter->convert('$100', 'JPY'));
    $this->assertSame('¥110', $this->converter->convert('100ドル', 'JPY'));
    $this->assertSame('¥110', $this->converter->convert('100USD', 'JPY'));
    $this->assertSame('¥110', $this->converter->convert('USD 100', 'JPY'));
    $this->assertSame('¥1100', $this->converter->convert('$1,000', 'JPY'));
    $this->assertSame('¥110.55', $this->converter->convert('$100.5', 'JPY'));
  }

  public function testConvertYenToDollar() {
    $this->assertSame('$100', $this->converter->convert('¥110', 'USD'));
    $this->assertSame('$100', $this->converter->convert('110円', 'USD'));
    $this->assertSame('$100', $this->converter->convert('110YEN', 'USD'));
    $this->assertSame('$100', $this->converter->convert('110yen', 'USD'));
    $this->assertSame('$100', $this->converter->convert('￥110', 'USD'));
  }

  public function testConvertEuroToYen() {
    $this->assertSame('¥120', $this->converter->convert('€100', 'JPY'));
    $this->assertSame('¥120', $this->converter->convert('100ユーロ', 'JPY'));
    $this->assertSame('¥120', $this->converter->convert('100EUR', 'JPY'));
  }
  public function testConvertPoundToYen() {
    $this->assertSame('¥130', $this->converter->convert('£100', 'JPY'));
    $this->assertSame('¥130', $this->converter->convert('100ポンド', 'JPY'));
    $this->assertSame('¥130', $this->converter->convert('100GBP', 'JPY'));
  }
  public function testConvertAUDtoJPY() {
    $this->assertSame('¥140', $this->converter->convert('A$100', 'JPY'));
    $this->assertSame('¥140', $this->converter->convert('AU$100', 'JPY'));
    $this->assertSame('¥140', $this->converter->convert('AUD100', 'JPY'));
  }
  public function testConvertCADtoJPY() {
    $this->assertSame('¥150', $this->converter->convert('C$100', 'JPY'));
    $this->assertSame('¥150', $this->converter->convert('CDN$100', 'JPY'));
    $this->assertSame('¥150', $this->converter->convert('CAD100', 'JPY'));
    $this->assertSame('¥150', $this->converter->convert('CA$100', 'JPY'));
  }
}

