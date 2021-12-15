<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Plugin\Locale;

use Magento\Framework\Locale\Format;
use Magento\Framework\Locale\Resolver;
use NumberFormatter;
use function is_string;

/**
 * Plugin for implement correct locale aware number parsing.
 */
final class LocalizedFormat
{
    private Resolver $localeResolver;

    public function __construct(Resolver $localeResolver)
    {
        $this->localeResolver = $localeResolver;
    }

    /**
     * Parse number with locale-aware parser.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeGetNumber(Format $format, $value): array
    {
        if (!is_string($value)) {
            return [$value];
        }

        $formatter = new NumberFormatter($this->localeResolver->getLocale(), NumberFormatter::DECIMAL);
        $number = $formatter->parse($value);

        // trigger core logic with dot handling for backward compatibility
        return [$number === false ? $value : (string)$number];
    }
}
