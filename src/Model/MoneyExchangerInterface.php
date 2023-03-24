<?php

namespace CommissionCalculatorApp\Model;

interface MoneyExchangerInterface
{
    public function exchange(float $amount, string $currencyFrom, string $currencyTo): float;
}
