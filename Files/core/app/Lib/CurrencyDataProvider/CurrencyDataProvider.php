<?php

namespace App\Lib\CurrencyDataProvider;

use App\Models\Currency;
use App\Models\Market;
use Exception;

abstract class CurrencyDataProvider
{

    /*
    |--------------------------------------------------------------------------
    | CurrencyDataProvider
    |--------------------------------------------------------------------------
    |
    |This class serves as a base class for various cryptocurrency and currency data providers.
    |It encapsulates common properties and methods required for retrieving currency-related information.
    |Classes like CoinMarketCap, CurrencyLayer, and others can extend this class to leverage its functionality.
    |
     */

    /**
     * The provider which via update currency data
     *
     * @var \Illuminate\Database\Eloquent\Collection
     *
     */
    public $provider;

    /**
     * The symbol(s) of the cryptocurrency.
     *
     * @var string|array
     *
     */
    public $cryptoSymbol;

    /**
     * The currency in which cryptocurrency
     * prices are converted.
     *
     * @var string
     *
     */
    public $cryptoConvertTo;

    /**
     * Fetches the cryptocurrency symbols
     * from the list of active currencies.
     *
     * @return array
     */
    protected function fetchCryptoSymbol()
    {
        return $this->cryptoCurrencyList()->select('symbol')->pluck('symbol')->toArray();
    }

    /**
     * Retrieve the list of active cryptocurrency
     * currencies with crypto symbol available or not.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     */
    protected function cryptoCurrencyList()
    {
        $query = Currency::crypto()->with('marketData')->whereHas('marketData')->active();
        if ($this->cryptoSymbol && gettype($this->cryptoSymbol) == 'array') {
            $query->whereIn('symbol', $this->cryptoSymbol);
        } elseif ($this->cryptoSymbol) {
            $query->where('symbol', $this->cryptoSymbol);
        }
        return $query->orderBy('last_update')->limit(25)->get();
    }

    /**
     * Fetches the currency used for
     * cryptocurrency price conversion.
     *
     * @return string
     *
     */
    protected function fetchCryptoConvertTo()
    {
        if (!$this->cryptoConvertTo) {
            return strtoupper(gs('cur_text'));
        }

        return strtoupper($this->cryptoConvertTo);
    }

    /**
     * Retrieve the list of active markets with associated data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     */
    protected function marketList()
    {
        return Market::active()->whereHas('pairs')->with('pairs.marketData', 'currency', 'pairs.coin')->get();
    }

    /**
     * Update the market data for a specific market.
     *
     * It defines the process of updating the market data for a specific market pair, using the provided
     * system market data, provider market data, and currency conversion information.
     *
     * @param object $systemMarketData
     * @param object $providerMarketData
     * @param string $convertTo
     * @return void
     *
     */
    abstract protected function updateMarketData($systemMarketData, $providerMarketData, $convertTo);

    /**
     * Make an API call to the data provider.
     *
     * It defines the process of making an API call to the data
     * provider, using the provided parameters. Subclasses must override this method
     * and provide their own implementation for interacting with the specific API of
     * the data provider.
     *
     * @param array
     * @return mixed
     */
    abstract protected function apiCall($parameters);

    /**
     * Sets an exception with the provided message.
     *
     * This protected method throws an exception with the provided message.
     * It is used internally within the CurrencyDataProvider class or its
     * subclasses to handle exceptional situations and provide error feedback.
     *
     * @param string
     * @throws Exception
     */
    protected function setException($message)
    {
        throw new Exception($message);
    }

    /**
     * Abstract method for configuring the currency data provider.
     *
     * This abstract method is meant to be implemented by subclasses of the
     * CurrencyDataProvider class. It defines the configuration process specific
     * to each data provider, such as setting API keys, endpoints, or other
     * provider-specific settings. Subclasses must override this method and
     * provide their own implementation.
     *
     * @return void
     */
    abstract protected function configuration();

    /**
     * Abstract method for updating the cryptocurrency prices.
     *
     * This abstract method is meant to be implemented by subclasses of the
     * CurrencyDataProvider class. It defines the process of updating the
     * cryptocurrency prices specific to each data provider. Subclasses must
     * override this method and provide their own implementation to fetch
     * and update the latest prices of cryptocurrencies.
     *
     * @return void
     */
    abstract protected function updateCryptoPrice();
}
