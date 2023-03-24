<?php

namespace CommissionCalculatorApp;

use CommissionCalculatorApp\Model\MoneyExchanger;
use CommissionCalculatorApp\Model\MoneyExchangerInterface;
use CommissionCalculatorApp\Model\TransactionCommissionCalculator;
use CommissionCalculatorApp\Model\TransactionCommissionCalculatorInterface;
use CommissionCalculatorApp\Service\JsonFileTransactionListParser;
use CommissionCalculatorApp\Service\Provider\ApilayerCurrencyExchangeProvider;
use CommissionCalculatorApp\Service\Provider\BinlistCardBinDataProvider;
use CommissionCalculatorApp\Service\Provider\CardBinDataProviderInterface;
use CommissionCalculatorApp\Service\Provider\CurrencyExchangeProviderInterface;
use CommissionCalculatorApp\Service\TransactionListParserInterface;

class CommissionCalculatorAppFactory
{
    private CommissionCalculatorAppConfig $config;

    public function __construct(CommissionCalculatorAppConfig $config)
    {
        $this->config = $config;
    }

    public function createTransactionParser(): TransactionListParserInterface
    {
        return new JsonFileTransactionListParser();
    }

    public function createCurrencyExchangeProvider(): CurrencyExchangeProviderInterface
    {
        return new ApilayerCurrencyExchangeProvider($this->config);
    }

    public function createMoneyExchangeCalculator(): MoneyExchangerInterface
    {
        return new MoneyExchanger(
            $this->createCurrencyExchangeProvider(),
        );
    }

    public function createCardBinDataProvider(): CardBinDataProviderInterface
    {
        return new BinlistCardBinDataProvider();
    }

    public function createTransactionCommissionCalculator(): TransactionCommissionCalculatorInterface
    {
        return new TransactionCommissionCalculator(
            $this->createMoneyExchangeCalculator(),
            $this->createCardBinDataProvider(),
            $this->config,
        );
    }
}
