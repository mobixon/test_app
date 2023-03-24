<?php

namespace CommissionCalculatorApp\Service;

interface TransactionListParserInterface
{
    /**
     * @param string $input
     *
     * @return \CommissionCalculatorApp\Transfer\TransactionDataTransfer[]
     */
    public function parse(string $input): array;
}
