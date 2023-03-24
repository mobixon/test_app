<?php

namespace CommissionCalculatorAppTest\Model;

use CommissionCalculatorApp\CommissionCalculatorAppConfig;
use CommissionCalculatorApp\Model\MoneyExchanger;
use CommissionCalculatorApp\Model\TransactionCommissionCalculator;
use CommissionCalculatorApp\Service\Provider\CardBinDataProviderInterface;
use CommissionCalculatorApp\Service\Provider\CurrencyExchangeProviderInterface;
use CommissionCalculatorApp\Transfer\TransactionDataTransfer;
use PHPUnit\Framework\TestCase;

class TransactionCommissionCalculatorTest extends TestCase
{

    /**
     * @dataProvider calculateDataProvider
     */
    public function testCalculate($transaction, $countryIso2Code, $rate, $expected)
    {
        //arrange
        $exchangeProvider = $this->createMock(CurrencyExchangeProviderInterface::class);
        $exchangeProvider->method('getExchangeRate')->willReturn((float) $rate);

        $cardBinDataProvider = $this->createMock(CardBinDataProviderInterface::class);
        $cardBinDataProvider->method('getCountryIso2CodeByBin')->willReturn($countryIso2Code);

        $moneyExchanger = new MoneyExchanger($exchangeProvider);

        $transactionCommissionCalculator = new TransactionCommissionCalculator(
            $moneyExchanger,
            $cardBinDataProvider,
            new CommissionCalculatorAppConfig(),
        );

        //act
        $commission = $transactionCommissionCalculator->calculate($transaction);

        //assert
        $this->assertEquals($expected, $commission->getAmount());
    }

    public static function calculateDataProvider(): array
    {
        return [
            [new TransactionDataTransfer("41417360", 100, 'EUR'), 'AT', 0.5, 1],
            [new TransactionDataTransfer("51417360", 400, 'USD'), 'US', 0.5, 16],
            [new TransactionDataTransfer("51417360", 400, 'USD'), 'DE', 0.5, 8],
            [new TransactionDataTransfer("41417360", 200, 'GBP'), 'AT', 0.5, 4],
            [new TransactionDataTransfer("41417360", 300, 'EUR'), 'AT', 1, 3],
            [new TransactionDataTransfer("51417360", 400, 'USD'), 'US', 1.5, 5.34],
            [new TransactionDataTransfer("51417360", 400, 'USD'), 'DE', 1.5, 2.67],
            [new TransactionDataTransfer("41417360", 200, 'GBP'), 'AT', 0.5, 4],
        ];
    }
}
