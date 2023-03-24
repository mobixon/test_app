<?php

namespace CommissionCalculatorApp\Model;

use CommissionCalculatorApp\CommissionCalculatorAppConfig;
use CommissionCalculatorApp\Service\Provider\CardBinDataProviderInterface;
use CommissionCalculatorApp\Transfer\TransactionCommissionDataTransfer;
use CommissionCalculatorApp\Transfer\TransactionDataTransfer;

class TransactionCommissionCalculator implements TransactionCommissionCalculatorInterface
{
    private MoneyExchangerInterface $moneyExchangeCalculator;
    private CardBinDataProviderInterface $cardBinDataProvider;
    private CommissionCalculatorAppConfig $config;


    public function __construct(
        MoneyExchangerInterface       $moneyExchangeCalculator,
        CardBinDataProviderInterface  $cardBinDataProvider,
        CommissionCalculatorAppConfig $config
    ) {
        $this->moneyExchangeCalculator = $moneyExchangeCalculator;
        $this->cardBinDataProvider = $cardBinDataProvider;
        $this->config = $config;
    }

    public function calculate(TransactionDataTransfer $transactionDataTransfer): TransactionCommissionDataTransfer
    {
        $amountInBaseCurrency = $this->moneyExchangeCalculator->exchange(
            $transactionDataTransfer->getAmount(),
            $transactionDataTransfer->getCurrency(),
            $this->config->getBaseCurrency()
        );
        $coefficient = $this->getCoefficient($transactionDataTransfer->getBin());

        $commissionAmount = $this->roundUp($amountInBaseCurrency * $coefficient);

        return new TransactionCommissionDataTransfer(
            $commissionAmount,
            $this->config->getBaseCurrency(),
            $transactionDataTransfer,
            $coefficient
        );
    }

    private function getCoefficient(string $bin): float
    {
        $coefficient = $this->config->getDefaultCommissionCoefficient();

        if ($this->isEuCountry($bin)) {
            $coefficient = $this->config->getEuCommissionCoefficient();
        }

        return $coefficient;
    }

    private function isEuCountry(string $bin): bool
    {
        $countryIso2Code = $this->cardBinDataProvider->getCountryIso2CodeByBin($bin);
        return in_array($countryIso2Code, $this->config->getEuCountriesIso2Codes());
    }

    private function roundUp(float $value): float
    {
        return ceil($value * 100) / 100;
    }
}
