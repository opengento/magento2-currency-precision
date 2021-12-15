<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Model;

use NumberFormatter;
use Opengento\CurrencyPrecision\Model\Config\CurrencyRoundingConfig;
use Opengento\CurrencyPrecision\Model\Config\Source\RoundingMode;

/**
 * Currency rounding service.
 */
final class CurrencyRounding
{
    private const ROUNDING_MODE_MAP = [
        RoundingMode::UP => NumberFormatter::ROUND_UP,
        RoundingMode::CEILING => NumberFormatter::ROUND_CEILING,
        RoundingMode::DOWN => NumberFormatter::ROUND_DOWN,
        RoundingMode::FLOOR => NumberFormatter::ROUND_FLOOR,
        RoundingMode::HALFUP => NumberFormatter::ROUND_HALFUP,
        RoundingMode::HALFEVEN => NumberFormatter::ROUND_HALFEVEN,
        RoundingMode::HALFDOWN => NumberFormatter::ROUND_HALFDOWN,
    ];

    private CurrencyRoundingConfig $currencyRoundingConfig;

    public function __construct(CurrencyRoundingConfig $currencyRoundingConfig)
    {
        $this->currencyRoundingConfig = $currencyRoundingConfig;
    }

    /**
     * Determine what precision has main currency unit.
     *
     * Main currency precision is defined by subunits. E.g. fo US dollar precision is 2 as 1 USD equals to 100 cents
     * so USD has 2 significant fraction digits. Japanese Yen (JPY) has precision 0 as no subunits currently in use.
     */
    public function getPrecision(string $currencyCode): int
    {
        return $this->createCurrencyFormatter($currencyCode)->getAttribute(NumberFormatter::MAX_FRACTION_DIGITS);
    }

    /**
     * Round currency to significant precision.
     *
     * Rounding method may be configured at admin page at
     */
    public function round(string $currencyCode, float $amount): float
    {
        $formatter = $this->createCurrencyFormatter($currencyCode);
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, $this->resolveRoundingMode());

        $formatted = $formatter->format($amount);
        if ($formatted !== false) {
            $rounded = $formatter->parse($formatted, NumberFormatter::TYPE_DOUBLE) ;

            return $rounded !== false ? $rounded : $amount;
        }

        return $amount;
    }

    /**
     * Create Intl Number Formatter for currency.
     */
    private function createCurrencyFormatter(string $currencyCode): NumberFormatter
    {
        return new NumberFormatter('@currency=' . $currencyCode, NumberFormatter::CURRENCY);
    }

    /**
     * Get Intl rounding mode.
     *
     * Read configured rounding mode and map to Intl constant value.
     */
    private function resolveRoundingMode(): int
    {
        return self::ROUNDING_MODE_MAP[$this->currencyRoundingConfig->getRoundingMode()] ?? NumberFormatter::ROUND_DOWN;
    }
}
