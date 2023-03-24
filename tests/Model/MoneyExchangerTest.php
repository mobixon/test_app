<?php

namespace CommissionCalculatorAppTest\Model;

use CommissionCalculatorApp\Model\MoneyExchanger;
use CommissionCalculatorApp\Service\Provider\CurrencyExchangeProviderInterface;
use PHPUnit\Framework\TestCase;

class MoneyExchangerTest extends TestCase
{
    /**
     * @dataProvider exchangeDataProvider
     */
    public function testExchange($amount, $currencyFrom, $currencyTo, $rate, $expected)
    {
        //arrange
        $exchangeProvider = $this->createMock(CurrencyExchangeProviderInterface::class);
        $exchangeProvider->method('getExchangeRate')->willReturn((float) $rate);

        $moneyExchanger = new MoneyExchanger($exchangeProvider);

        //act
        $result = $moneyExchanger->exchange($amount, $currencyFrom, $currencyTo);

        //assert
        $this->assertEquals($expected, $result);
    }

    public static function exchangeDataProvider(): array
    {
        return [
            [100, 'EUR', 'USD', 0.5, 200],
            [100, 'USD', 'EUR', 2, 50],
            [100, 'EUR', 'EUR', 1, 100],
        ];
    }
}
