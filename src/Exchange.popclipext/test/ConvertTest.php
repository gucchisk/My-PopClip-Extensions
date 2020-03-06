<?php
require('../convert.php');

use PHPUnit\Framework\TestCase;

class ConvertTest extends TestCase {
    static $test_json = <<<EOL
{"quotes":[
{"currencyPairCode":"USDJPY","ask":"1.1"},
{"currencyPairCode":"EURJPY","ask":"1.2"}
]}
EOL;
    protected $converter;

    protected function setUp(): void
    {
        $this->converter = new Converter(self::$test_json);
    }
    
    public function testCovertDollar() {
        $this->assertSame('¥110', $this->converter->convert('$100'));
        $this->assertSame('¥110', $this->converter->convert('100ドル'));
        $this->assertSame('¥110', $this->converter->convert('100USD'));
        $this->assertSame('¥1100', $this->converter->convert('$1,000'));
        $this->assertSame('¥110.55', $this->converter->convert('$100.5'));
    }

    public function testConvertEuro() {
        $this->assertSame('¥120', $this->converter->convert('€100'));
        $this->assertSame('¥120', $this->converter->convert('100ユーロ'));
        $this->assertSame('¥120', $this->converter->convert('100EUR'));
    }
}

