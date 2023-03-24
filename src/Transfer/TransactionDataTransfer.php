<?php

namespace CommissionCalculatorApp\Transfer;

class TransactionDataTransfer
{
    public function __construct(
        protected string $bin,
        protected string $amount,
        protected string $currency
    ) { }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
