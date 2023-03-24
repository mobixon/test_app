<?php

namespace CommissionCalculatorApp\Service\Provider;

use CommissionCalculatorApp\Exception\CommissionCalculatorAppException;

class BinlistCardBinDataProvider implements CardBinDataProviderInterface
{
    protected const BASE_URL = 'https://lookup.binlist.net';

    /**
     * @param int $cardBin
     *
     * @return string
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    public function getCountryIso2CodeByBin(int $cardBin): string
    {
        $cardBinJsonData = $this->loadCardBinJsonData($cardBin);
        $cardBinArrayData = $this->parseCardBinData($cardBinJsonData);

        if (!isset($cardBinArrayData['country']) || !isset($cardBinArrayData['country']['alpha2'])
        ) {
            throw new CommissionCalculatorAppException('Unable to parse country ISO2code for card bin: ' . $cardBin);
        }

        return $cardBinArrayData['country']['alpha2'];
    }

    /**
     * @param int $cardBin
     *
     * @return string
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    protected function loadCardBinJsonData(int $cardBin): string
    {
        /* Example implementation, can be changed to any other HTTP client based package like Guzzle */
        $cardBinJsonData = file_get_contents( static::BASE_URL . '/' . $cardBin);

        if (!$cardBinJsonData) {
            throw new CommissionCalculatorAppException('Unable to load card bin data.');
        }

        return $cardBinJsonData;
    }

    /**
     * @param string $cardBinJsonData
     *
     * @return array
     * @throws \CommissionCalculatorApp\Exception\CommissionCalculatorAppException
     */
    protected function parseCardBinData(string $cardBinJsonData): array
    {
        $cardBinArrayData = json_decode($cardBinJsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new CommissionCalculatorAppException('Unable to parse card bin data.');
        }

        return $cardBinArrayData;
    }
}
