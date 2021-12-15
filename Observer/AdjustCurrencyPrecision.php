<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Opengento\CurrencyPrecision\Model\CurrencyRounding;

/**
 * Adjust default currency options used during currency instantiation to specify correct precision.
 */
final class AdjustCurrencyPrecision implements ObserverInterface
{
    private CurrencyRounding $currencyRounding;

    public function __construct(CurrencyRounding $currencyRounding)
    {
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * Get options to configure required precision.
     */
    public function execute(Observer $observer): void
    {
        $event = $observer->getEvent();
        $currencyCode = $event->getData('base_code');
        $currencyOptions = $event->getData('currency_options');

        if ($currencyCode !== null && $currencyOptions instanceof DataObject) {
            $currencyOptions->setData('precision', $this->currencyRounding->getPrecision($currencyCode));
        }
    }
}
