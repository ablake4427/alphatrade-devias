<?php

namespace App\Lib\CurrencyDataProvider;

use App\Constants\Status;
use App\Events\MarketDataEvent;
use App\Lib\CurlRequest;
use App\Models\Currency;
use App\Models\CurrencyDataProvider as CurrencyDataProviderModel;
use App\Models\MarketData;
use Exception;

class CoinmarketCap extends CurrencyDataProvider
{
    /*
    |--------------------------------------------------------------------------
    | CoinmarketCap
    |--------------------------------------------------------------------------
    |
    | This class extends the `CurrencyDataProvider` class and serves as a data provider for
    | retrieving cryptocurrency data from the CoinmarketCap service. It implements the necessary
    | methods to fetch cryptocurrency symbols, convert currency, and update market data specific
    | to the CoinmarketCap data provider.
    |
     */

    /**
     * Update cryptocurrency prices and market data.
     *
     * @return void
     * @throws Exception if there is an error with the API call or data processing.
     *
     */
    public function updateCryptoPrice()
    {
        $convertTo          = $this->fetchCryptoConvertTo();
        $cryptoCurrencyList = $this->cryptoCurrencyList();
        $symbol             = $cryptoCurrencyList->pluck('symbol')->implode(',');

        $parameters = [
            'symbol'  => $symbol,
            'convert' => $convertTo,
        ];

        $data = $this->apiCall($parameters);

        if (@$data->status->error_code != 0) {
            $this->setException(@$data->status->error_message);
        }

        $updatedMarketData = [];

        foreach ($data->data ?? [] as $item) {

            $symbol         = strtoupper($item->symbol);
            $cryptoCurrency = $cryptoCurrencyList->where('symbol', $symbol)->first();

            if (!$cryptoCurrency) {
                continue;
            }

            $marketData = $cryptoCurrency->marketData;
            $itemData   = $item->quote->$convertTo;

            if (!$marketData || !$itemData) {
                continue;
            }
            $cryptoCurrency->ranking     = $item->cmc_rank;
            $cryptoCurrency->rate        = $itemData->price;
            $cryptoCurrency->last_update = time();
            $cryptoCurrency->save();

            $updatedMarketData[] = $this->updateMarketData($marketData, $itemData, $convertTo);
        }

        try {
            event(new MarketDataEvent($updatedMarketData));
        } catch (Exception $ex) {
            $this->setException($ex->getMessage());
        }
        echo 'CRYPTO PRICE UPDATE <br/>  ' . date("h:m:s") . ' ';
    }

    /**
     * Update market data for all active markets.
     *
     * @return void
     * @throws Exception if there is an error with the API call or data processing.
     *
     */
    public function updateMarkets()
    {
        $conflagration     = $this->configuration();
        $markets           = $this->marketList();
        $updatedMarketData = [];

        foreach ($markets as $market) {

            $pairs     = $market->pairs;
            $symbol    = $pairs->pluck('marketData.symbol')->implode(',');
            $convertTo = $market->currency->symbol;

            $parameters = [
                'symbol'  => $symbol,
                'convert' => $convertTo,
            ];

            $data = $this->apiCall($parameters, $conflagration);
            if ($data->status->error_code != 0) {
                $this->setException(@$data->status->error_message);
            }
            foreach ($data->data ?? [] as $item) {
                $symbol     = strtoupper($item->symbol);
                $pair       = $pairs->where('coin.symbol', $symbol)->first();
                $marketData = @$pair->marketData;
                $itemData   = @$item->quote->$convertTo;
                if (!$pair || !$marketData || !$itemData) {
                    continue;
                }
                $updatedMarketData[] = $this->updateMarketData($marketData, $itemData, $convertTo);
            }
        }

        try {
            event(new MarketDataEvent($updatedMarketData));
        } catch (Exception $ex) {
            $this->setException($ex->getMessage());
        }

        echo 'CRYPTO PRICE UPDATE <br/> ' . date("h:m:s");
    }

    /**
     * Make an API call to the CoinmarketCap API.
     *
     * @param array $parameters
     * @param array|null $conflagration
     * @return object
     *
     */
    public function apiCall($parameters = null, $conflagration = null, $endPoint = "cryptocurrency/quotes/latest")
    {
        if (!$conflagration) {
            $conflagration = $this->configuration();
        }

        $url     = $conflagration['base_url'] . $endPoint;
        $apiKey  = $conflagration['api_key'];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY:' . $apiKey,
        ];
        $qs       = $parameters ? http_build_query($parameters) : "";
        $response = CurlRequest::curlContent("{$url}?{$qs}", $headers);
        return json_decode($response);
    }

    /**
     * Update the market data for a specific market with the provided data.
     *
     * @param \App\Models\MarketData $systemMarketData
     * @param mixed $providerMarketData
     * @param string $convertTo
     * @return string
     *
     */
    protected function updateMarketData($systemMarketData, $providerMarketData, $convertTo)
    {
        $systemMarketData->last_price              = $systemMarketData->price;
        $systemMarketData->last_percent_change_1h  = $systemMarketData->percent_change_1h;
        $systemMarketData->last_percent_change_24h = $systemMarketData->percent_change_24h;
        $systemMarketData->last_percent_change_7d  = $systemMarketData->percent_change_7d;

        $htmlClasses = [
            'price_change'       => upOrDown($providerMarketData->price, $systemMarketData->price),
            'percent_change_1h'  => upOrDown($providerMarketData->percent_change_1h, $systemMarketData->percent_change_1h),
            'percent_change_24h' => upOrDown($providerMarketData->percent_change_24h, $systemMarketData->percent_change_24h),
            'percent_change_7d'  => upOrDown($providerMarketData->percent_change_7d, $systemMarketData->percent_change_7d),
        ];

        $systemMarketData->price              = abs($providerMarketData->price);
        $systemMarketData->percent_change_1h  = abs($providerMarketData->percent_change_1h);
        $systemMarketData->percent_change_24h = abs($providerMarketData->percent_change_24h);
        $systemMarketData->percent_change_7d  = abs($providerMarketData->percent_change_7d);
        $systemMarketData->market_cap         = abs($providerMarketData->market_cap);
        $systemMarketData->volume_24h         = abs($providerMarketData->volume_24h);
        $systemMarketData->volume_change_24h  = $providerMarketData->volume_change_24h;
        $systemMarketData->html_classes       = $htmlClasses;

        $systemMarketData->save();

        return json_encode([
            'symbol'             => $systemMarketData->symbol,
            'price'              => $systemMarketData->price,
            'percent_change_1h'  => $systemMarketData->percent_change_1h,
            'percent_change_24h' => $systemMarketData->percent_change_24h,
            'html_classes'       => $systemMarketData->html_classes,
            'id'                 => $systemMarketData->id,
            'market_cap'         => $systemMarketData->market_cap,
            'html_classes'       => $systemMarketData->html_classes,
            'last_price'         => $systemMarketData->last_price,
        ]);
    }

    /**
     * Get the configuration parameters for the CoinmarketCap API.
     *
     * @return array
     *
     */
    public function configuration()
    {
        $provider = $this->provider ? $this->provider : CurrencyDataProviderModel::where('alias', "CoinmarketCap")->first();
        return [
            'api_key'  => @$provider->configuration->api_key->value,
            'base_url' => "https://pro-api.coinmarketcap.com/v1/",
        ];
    }

    /**
     * import crypto & fiat currency from CoinmarketCap API.
     *
     * @return integer
     *
     */

    public function import($parameters, $type)
    {

        $endPoint = $type == Status::CRYPTO_CURRENCY ? 'cryptocurrency/listings/latest' : 'fiat/map';
        $data     = $this->apiCall($parameters, null, $endPoint);

        if (@$data->status->error_code != 0) {
            $this->setException(@$data->status->error_message);
        }

        $currencies = [];
        $marketData = [];
        $now        = now();

        foreach ($data->data as $item) {
            $currencies[] = [
                'type'       => @$type,
                'name'       => @$item->name,
                'symbol'     => @$item->symbol,
                'sign'       => @$item->sign ?? '',
                'ranking'    => @$item->cmc_rank ?? 0,
                'rate'       => @$item->quote->USD->price ?? 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $importCount = Currency::insertOrIgnore($currencies);
        if ($type == Status::FIAT_CURRENCY || $importCount <= 0) {
            return $importCount;
        }

        $marketData = [];

        foreach ($currencies as $currency) {
            $currency = Currency::where('symbol', @$currency['symbol'])->first();

            if (!$currency) {
                continue;
            }

            if (MarketData::where('pair_id', 0)->where('currency_id', $currency->id)->exists()) {
                continue;
            }

            $marketData[] = [
                'currency_id' => $currency->id,
                'symbol'      => @$currency->symbol,
                'price'       => @$currency->rate,
                'pair_id'     => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }
        MarketData::insertOrIgnore($marketData);
        return $importCount;
    }
}
