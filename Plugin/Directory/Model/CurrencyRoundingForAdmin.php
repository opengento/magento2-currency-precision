<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Plugin\Directory\Model;

use Magento\Directory\Model\Currency;
use Magento\Directory\Model\PriceCurrency;
use Magento\Store\Model\StoreManager;
use Opengento\CurrencyPrecision\Model\CurrencyRounding as Model;

/**
 * Replace standard rounding method with rounding based on currency precision and with configured rounding method.
 */
class CurrencyRoundingForAdmin
{
    /**
     * @var CurrencyRounding
     */
    private $model;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @param Model $model
     * @param StoreManager $storeManager
     */
    public function __construct(
        Model $model,
        StoreManager $storeManager
    ) {
        $this->model = $model;
        $this->storeManager = $storeManager;
    }

    /**
     * Override original method to apply correct rounding logic.
     *
     * @param PriceCurrency $priceCurrency
     * @param \Closure $proceed
     * @param float $amount
     * @param string $scope
     * @param string $currency
     * @param int $precision
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundConvertAndRound(
        PriceCurrency $priceCurrency,
        \Closure $proceed,
        $amount,
        $scope = null,
        $currency = null,
        $precision = PriceCurrency::DEFAULT_PRECISION
    ) {
        $targetCurrency = $priceCurrency->getCurrency($scope, $currency);
        $convertedAmount = $this->storeManager->getStore($scope)->getBaseCurrency()->convert($amount, $targetCurrency);
        if ($targetCurrency->getCode() === null) {
            return $convertedAmount;
        }

        $roundedAmount = $this->round($targetCurrency->getCode(), (float)$convertedAmount);
        return $roundedAmount;
    }

    /**
     * Override original method to apply correct rounding logic.
     *
     * @param PriceCurrency $priceCurrency
     * @param \Closure $proceed
     * @param float $amount
     * @return float
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundRound(
        PriceCurrency $priceCurrency,
        \Closure $proceed,
        $amount
    ) {
        $currency = $this->currency;
        $currencyCode = null;
        if ($currency !== null) {
            $currencyCode = $currency->getCode();
        }

        if ($currencyCode === null) {
            return (float)$amount;
        }

        return $this->round($currencyCode, (float)$amount);
    }

    /**
     * Set currency object when getCurrency called.
     *
     * @param \Magento\Directory\Model\PriceCurrency $subject
     * @param $result
     * @param bool|int|ScopeInterface|string|null $scope
     * @param AbstractModel|string|null $currency
     */
    public function afterGetCurrency(
        PriceCurrency $subject,
        $result,
        $scope = null,
        $currency = null
    ) {
        $this->currency = $result;
        return $result;
    }



    /**
     * Round currency using rounding service.
     */
    private function round(string $currencyCode, float $amount): float
    {
        return $this->model->round($currencyCode, $amount);
    }

}
