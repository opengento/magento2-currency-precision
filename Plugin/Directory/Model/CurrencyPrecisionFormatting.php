<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Plugin\Directory\Model;

use Magento\Directory\Model\Currency;
use Opengento\CurrencyPrecision\Model\CurrencyRounding;

/**
 * Add currency precision option for formatting.
 */
final class CurrencyPrecisionFormatting
{
    private CurrencyRounding $currencyRounding;

    public function __construct(CurrencyRounding $currencyRounding)
    {
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * Override requested precision to use system defined precision.
     *
     * Only Currency::formatTxt should be pluginized as all other formatting methods should use its result.
     *
     * @param Currency $currency
     * @param float|string $price
     * @param array $options
     * @return array
     */
    public function beforeFormatTxt(Currency $currency, $price, array $options = []): array
    {
        $currencyCode = $currency->getCode();
        //round before formatting to apply configured rounding mode.
        if ($currencyCode !== null) {
            $price = $this->currencyRounding->round($currencyCode, (float)$price);
            $options['precision'] = $this->currencyRounding->getPrecision($currencyCode);
        }

        return [$price, $options];
    }
}
