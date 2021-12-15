<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Opengento\CurrencyPrecision\Model\Config\CurrencyRoundingConfig;
use Opengento\CurrencyPrecision\Model\Config\Source\RoundingMode;
use Opengento\CurrencyPrecision\Model\CurrencyRounding;
use PHPUnit\Framework\TestCase;

/**
 * Currency Rounding Tests
 */
class CurrencyRoundingTest extends TestCase
{
    /**
     * @dataProvider currencyPrecisions
     */
    public function testGetCurrencyPrecision($currencyCode, $expectedPrecision): void
    {
        $scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $config = new CurrencyRoundingConfig($scopeConfig);
        $currencyPrecision = new CurrencyRounding($config);

        $this->assertEquals($expectedPrecision, $currencyPrecision->getPrecision($currencyCode));
    }

    /**
     * @dataProvider roundingExamples
     */
    public function testRound(
        string $currency,
        string $roundingMode,
        float $amount,
        float $expectedResult
    ): void {
        $scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturn($roundingMode);
        $config = new CurrencyRoundingConfig($scopeConfig);
        $handler = new CurrencyRounding($config);

        $this->assertEquals($expectedResult, $handler->round($currency, $amount));
    }

    /**
     * List of currencies with their precisions.
     */
    public function currencyPrecisions(): array
    {
        return [
            ['USD', 2],
            ['JPY', 0],
        ];
    }

    /**
     * Table of rounding for currencies in different modes.
     */
    public function roundingExamples(): array
    {
        return [
            ['USD', RoundingMode::UP, 1111.237, 1111.24],
            ['USD', RoundingMode::CEILING, 1111.237, 1111.24],
            ['USD', RoundingMode::UP, -1111.237, -1111.24],
            ['USD', RoundingMode::CEILING, -1111.237, -1111.23],
            ['USD', RoundingMode::DOWN, 1111.237, 1111.23],
            ['USD', RoundingMode::FLOOR, 1111.237, 1111.23],
            ['USD', RoundingMode::DOWN, -1111.237, -1111.23],
            ['USD', RoundingMode::FLOOR, -1111.237, -1111.24],
            ['USD', RoundingMode::HALFUP, 1111.235, 1111.24],
            ['USD', RoundingMode::HALFUP, 1111.245, 1111.25],
            ['USD', RoundingMode::HALFEVEN, 1111.235, 1111.24],
            ['USD', RoundingMode::HALFEVEN, 1111.245, 1111.24],
            ['USD', RoundingMode::HALFDOWN, 1111.235, 1111.23],
            ['USD', RoundingMode::HALFDOWN, 1111.245, 1111.24],
            ['JPY', RoundingMode::UP, 1234.7, 1235],
            ['JPY', RoundingMode::CEILING, 1234.7, 1235],
            ['JPY', RoundingMode::UP, -1234.7, -1235],
            ['JPY', RoundingMode::CEILING, -1234.7, -1234],
            ['JPY', RoundingMode::DOWN, 1234.7, 1234],
            ['JPY', RoundingMode::FLOOR, 1234.7, 1234],
            ['JPY', RoundingMode::DOWN, -1234.7, -1234],
            ['JPY', RoundingMode::FLOOR, -1234.7, -1235],
            ['JPY', RoundingMode::HALFUP, 1233.5, 1234],
            ['JPY', RoundingMode::HALFUP, 1234.5, 1235],
            ['JPY', RoundingMode::HALFEVEN, 1233.5, 1234],
            ['JPY', RoundingMode::HALFEVEN, 1234.5, 1234],
            ['JPY', RoundingMode::HALFDOWN, 1233.5, 1233],
            ['JPY', RoundingMode::HALFDOWN, 1234.5, 1234],
        ];
    }
}
