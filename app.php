<?php
use CommissionCalculatorApp\CommissionCalculatorApp;

include_once __DIR__ . '/env.php';
require_once __DIR__ . '/vendor/autoload.php';

(new CommissionCalculatorApp())->run($argv[1]);
