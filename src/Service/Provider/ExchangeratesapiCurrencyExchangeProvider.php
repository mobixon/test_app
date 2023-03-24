<?php

namespace CommissionCalculatorApp\Service\Provider;

use CommissionCalculatorApp\CommissionCalculatorAppConfig;
use CommissionCalculatorApp\Exception\CommissionCalculatorAppException;

class ExchangeratesapiCurrencyExchangeProvider implements CurrencyExchangeProviderInterface
{
    protected const BASE_URL = 'https://api.exchangeratesapi.io';

    protected const ENV_API_KEY_NAME = 'EXCHANGERATESAPI_API_KEY';

    protected CommissionCalculatorAppConfig $config;

    public function __construct(CommissionCalculatorAppConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     *
     * @return float
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    public function getExchangeRate(string $currencyFrom, string $currencyTo): float
    {
        if ($currencyFrom === $currencyTo) {
            return 1;
        }

        if ($currencyFrom !== $this->config->getBaseCurrency()) {
            throw new CommissionCalculatorAppException('Loading exchange rate data for currency: ' . $currencyFrom . ' is not supported');
        }

        return $this->getExchangeRateForBaseCurrency($currencyTo);
    }

    /**
     * @param string $currencyTo
     *
     * @return float
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    private function getExchangeRateForBaseCurrency(string $currencyTo): float
    {
        $ratesDataArray = $this->parseRatesData($this->loadLatestRatesDataForBaseCurrency());

        if (
            !isset($ratesDataArray['rates'][$currencyTo])
            || !is_numeric($ratesDataArray['rates'][$currencyTo])
        ) {
            $providerMessage = $ratesDataArray['error']['info'] ?? '';
            throw new CommissionCalculatorAppException('Unable to load rate data for currency: ' . $currencyTo . '. ' . $providerMessage);
        }

        return $ratesDataArray['rates'][$currencyTo];
    }

    /**
     * @return string
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    private function loadLatestRatesDataForBaseCurrency(): string
    {
        $apiKey = $this->getApiKey();

        /* Example implementation, can be changed to any other HTTP client based package like Guzzle */
        $ratesData = file_get_contents(self::BASE_URL . '/latest?access_key=' . $apiKey . 'base=' . $this->config->getBaseCurrency());

        if (!$ratesData) {
            throw new CommissionCalculatorAppException('Unable to load rates data');
        }

        return $ratesData;
    }

    /**
     * @param string $ratesData
     *
     * @return array
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    private function parseRatesData(string $ratesData): array
    {
        $ratesData = json_decode($ratesData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new CommissionCalculatorAppException('Unable to parse rates data');
        }

        return $ratesData;
    }

    private function getApiKey(): string
    {
        return getenv(self::ENV_API_KEY_NAME) ?: '';
    }
}
