<?php

namespace CommissionCalculatorApp\Service\Provider;

use CommissionCalculatorApp\CommissionCalculatorAppConfig;
use CommissionCalculatorApp\Exception\CommissionCalculatorAppException;

class ApilayerCurrencyExchangeProvider implements CurrencyExchangeProviderInterface
{
    protected const BASE_URL = 'https://api.apilayer.com';

    protected const ENV_API_KEY_NAME = 'APILAYERCURRENCY_API_KEY';

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
            $providerMessage = $ratesDataArray['error']['info'] ?? json_encode($ratesDataArray);
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
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::BASE_URL . '/exchangerates_data/latest?base=' . $this->config->getBaseCurrency(),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: $apiKey"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $ratesData = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if (!$ratesData) {
            throw new CommissionCalculatorAppException('Unable to load rates data. ' . $error);
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
        return $_ENV[self::ENV_API_KEY_NAME] ?? '';
    }
}
