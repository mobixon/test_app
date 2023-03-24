<?php

namespace CommissionCalculatorApp\Service;

use CommissionCalculatorApp\Exception\CommissionCalculatorAppException;
use CommissionCalculatorApp\Transfer\TransactionDataTransfer;

class JsonFileTransactionListParser implements TransactionListParserInterface
{
    /**
     * @param string $input
     *
     * @return \CommissionCalculatorApp\Transfer\TransactionDataTransfer[]
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    public function parse(string $input): array
    {
        $transactions = [];
        $file = fopen($input, 'r');
        while (($line = fgets($file)) !== false) {
            $transactions[] = $this->parseTransaction($line);
        }
        fclose($file);

        return $transactions;
    }

    /**
     * @param string $line
     *
     * @return \CommissionCalculatorApp\Transfer\TransactionDataTransfer
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    private function parseTransaction(string $line): TransactionDataTransfer
    {
        $lineDataArray = json_decode($line, true);

        if (
            json_last_error() !== JSON_ERROR_NONE
            || !isset($lineDataArray['bin'])
            || !isset($lineDataArray['amount'])
            || !isset($lineDataArray['currency'])
        ) {
            throw new CommissionCalculatorAppException('Invalid transaction data');
        }

        return new TransactionDataTransfer($lineDataArray['bin'], $lineDataArray['amount'], $lineDataArray['currency']);
    }
}
