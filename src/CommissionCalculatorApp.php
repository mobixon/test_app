<?php

namespace CommissionCalculatorApp;

use Throwable;

class CommissionCalculatorApp
{

    /**
     * @var CommissionCalculatorAppFactory
     */
    private CommissionCalculatorAppFactory $factory;
    private CommissionCalculatorAppConfig $config;

    public function __construct()
    {
        $this->config = new CommissionCalculatorAppConfig();
        $this->factory = new CommissionCalculatorAppFactory($this->config);
    }

    public function run(string $input): void
    {
        $transactions = $this->getFactory()->createTransactionParser()->parse($input);
        $commissionCalculator = $this->getFactory()->createTransactionCommissionCalculator();

        foreach ($transactions as $transaction) {
            try {
                $commission = $commissionCalculator->calculate($transaction);
                echo $commission->getAmount() . PHP_EOL;
            } catch (Throwable $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function getFactory(): CommissionCalculatorAppFactory
    {
        return $this->factory;
    }

}
