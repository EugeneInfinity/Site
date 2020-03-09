<?php
/**
 * Created by PhpStorm.
 * User: its
 * Date: 30.01.19
 * Time: 10:55
 */

namespace App\Helpers\Currency;

class Currency
{
    protected $config;

    protected $symbol = null;

    /**
     * Currency constructor.
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }
        $this->app = $app;

        $this->config = $this->app['config'];
    }

    /**
     * @param $value
     * @param null $code
     * @param null $symbol
     * @return string
     */
    public function format($value, $code = null, $symbol = null)
    {
        $code = $this->prepareCode($code);

        $precision = $this->config->get("currency.currencies.$code.precision", 0);
        $decimalSeparator = $this->config->get("currency.currencies.$code.decimalSeparator", "");
        $thousandSeparator = $this->config->get("currency.currencies.$code.thousandSeparator", "");

        $symbol = $symbol ?? $this->config->get("currency.currencies.$code.symbol", "");

        $result = number_format($value / 100, $precision, $decimalSeparator, $thousandSeparator);

        return $symbol ? (($this->config->get("currency.currencies.$code.symbolPlacement") == 'after') ? $result.$symbol: $symbol.$result):
            $result;

    }

    /**
     * @param null $code
     * @return null|string
     */
    protected function prepareCode($code = null)
    {
        if (! $code) {
            $code =  $this->config->get("currency.default", 'USD');
        }

        if (array_key_exists($code, $this->config->get("currency.currencies", []))) {
            return $code;
        }

        return 'USD';
    }
}