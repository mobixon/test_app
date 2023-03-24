<?php

namespace CommissionCalculatorApp;

class CommissionCalculatorAppConfig
{
    private const ENV_BASE_CURRENCY = 'BASE_CURRENCY';
    private const DEFAULT_BASE_CURRENCY = 'EUR';
    private const EU_COMMISSION_COEFFICIENT = 0.01;
    private const DEFAULT_COMMISSION_COEFFICIENT = 0.02;

    private const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];


    public function getBaseCurrency(): string
    {
        return getenv(self::ENV_BASE_CURRENCY) ?: self::DEFAULT_BASE_CURRENCY;
    }

    /**
     * @return array<string>
     */
    public function getEuCountriesIso2Codes(): array
    {
        return self::EU_COUNTRIES;
    }

    public function getEuCommissionCoefficient(): float
    {
        return self::EU_COMMISSION_COEFFICIENT;
    }

    public function getDefaultCommissionCoefficient(): float
    {
        return self::DEFAULT_COMMISSION_COEFFICIENT;
    }
}
