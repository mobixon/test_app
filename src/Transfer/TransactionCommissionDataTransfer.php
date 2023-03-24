<?php

namespace CommissionCalculatorApp\Transfer;

class TransactionCommissionDataTransfer
{
    public function __construct(
        protected float  $amount,
        protected string $currency,
        protected TransactionDataTransfer $transaction,
        protected float  $coefficient,
    ) { }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTransaction(): TransactionDataTransfer
    {
        return $this->transaction;
    }

    public function getCoefficient(): float
    {
        return $this->coefficient;
    }
}
