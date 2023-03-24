<?php

namespace CommissionCalculatorApp\Model;

use CommissionCalculatorApp\Service\Provider\CurrencyExchangeProviderInterface;

class MoneyExchanger implements MoneyExchangerInterface
{
    protected CurrencyExchangeProviderInterface $currencyExchangeRateProvider;

    public function __construct(CurrencyExchangeProviderInterface $currencyExchangeRateProvider)
    {
        $this->currencyExchangeRateProvider = $currencyExchangeRateProvider;
    }

    public function exchange(float $amount, string $currencyFrom, string $currencyTo): float
    {
        if ($currencyFrom === $currencyTo) {
            return $amount;
        }

        return $amount / $this->currencyExchangeRateProvider->getExchangeRate($currencyTo, $currencyFrom);
    }
}
