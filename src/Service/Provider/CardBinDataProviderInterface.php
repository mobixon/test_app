<?php

namespace CommissionCalculatorApp\Service\Provider;

interface CardBinDataProviderInterface
{
    public function getCountryIso2CodeByBin(int $cardBin): string;
}
