<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Plugin\Directory\Model;

use Exception;
use Magento\Directory\Model\PriceCurrency;
use Opengento\CurrencyPrecision\Model\CurrencyRounding as CurrencyRoundingModel;

/**
 * Replace standard rounding method with rounding based on currency precision and with configured rounding method.
 */
final class CurrencyRounding
{
    private CurrencyRoundingModel $currencyRounding;

    public function __construct(CurrencyRoundingModel $currencyRounding)
    {
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * Override original method to apply correct rounding logic.
     *
     * @param PriceCurrency $priceCurrency
     * @param callable $proceed
     * @param float|int $amount
     * @param string|null $scope
     * @param string|null $currency
     * @param int $precision
     * @return float
     * @throws Exception
     */
    public function aroundConvertAndRound(
        PriceCurrency $priceCurrency,
        callable $proceed,
        $amount,
        $scope = null,
        $currency = null,
        $precision = PriceCurrency::DEFAULT_PRECISION
    ): float {
        $currencyCode = $priceCurrency->getCurrency($scope, $currency)->getCode();

        return $currencyCode === null
            ? (float)($proceed($amount, $scope, $currency, $precision))
            : $this->round($currencyCode, $priceCurrency->convert($amount, $scope, $currency));
    }

    /**
     * Override original method to apply correct rounding logic.
     */
    public function aroundRound(PriceCurrency $priceCurrency, callable $proceed, $amount): float
    {
        $currencyCode = $priceCurrency->getCurrency()->getCode();

        return $currencyCode === null ? $proceed($amount) : $this->round($currencyCode, (float)$amount);
    }

    /**
     * Round currency using rounding service.
     */
    private function round(string $currencyCode, float $amount): float
    {
        return $this->currencyRounding->round($currencyCode, $amount);
    }
}
