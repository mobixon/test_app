<?php

namespace CommissionCalculatorApp\Model;

use CommissionCalculatorApp\Transfer\TransactionCommissionDataTransfer;
use CommissionCalculatorApp\Transfer\TransactionDataTransfer;

interface TransactionCommissionCalculatorInterface
{
    public function calculate(TransactionDataTransfer $transactionDataTransfer): TransactionCommissionDataTransfer;
}
