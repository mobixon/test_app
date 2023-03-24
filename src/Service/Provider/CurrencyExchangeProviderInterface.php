<?php

namespace CommissionCalculatorApp\Service\Provider;

interface CurrencyExchangeProviderInterface
{
    public function getExchangeRate(string $currencyFrom, string $currencyTo): float;
}
