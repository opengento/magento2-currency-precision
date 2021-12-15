<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * System configuration of currencies rounding.
 */
final class CurrencyRoundingConfig
{
    private const CONFIG_PATH_CURRENCY_ROUNDING_MODE = 'currency/options/rounding_mode';

    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Return configured rounding mode for currencies.
     */
    public function getRoundingMode(?int $websiteId = null): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_CURRENCY_ROUNDING_MODE,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
