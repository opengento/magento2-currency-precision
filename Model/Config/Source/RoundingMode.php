<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Opengento\CurrencyPrecision\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Phrase;

/**
 * Rounding Mode Types.
 */
final class RoundingMode implements OptionSourceInterface
{
    /**
     * Ceiling rounding mode.
     *
     * Round towards positive infinity.
     */
    public const CEILING = 'ceiling';

    /**
     * Down rounding mode.
     *
     * Round towards zero.
     */
    public const DOWN = 'down';

    /**
     * Floor rounding mode.
     *
     * Round towards negative infinity.
     */
    public const FLOOR = 'floor';

    /**
     * Half Down rounding mode.
     *
     * Round towards "nearest neighbor" unless both neighbors are equidistant,
     * in which case round down.
     */
    public const HALFDOWN = 'halfdown';

    /**
     * Half Even rounding mode.
     * Round towards "nearest neighbor" unless both neighbors are equidistant,
     * in which case ound towards the even neighbor.
     */
    public const HALFEVEN = 'halfeven';

    /**
     * Half Up rounding mode.
     *
     * Round towards "nearest neighbor" unless both neighbors are equidistant,
     * in which case round up.
     */
    public const HALFUP = 'halfup';

    /**
     * Up rounding mode.
     *
     * Round away from zero.
     */
    public const UP = 'up';

    private array $options = [];

    /**
     * List available currency rounding modes.
     */
    public function toOptionArray(): array
    {
        return $this->options ?? $this->options = $this->buildOptions();
    }

    private function buildOptions(): array
    {
        $options = [];
        foreach ($this->options() as $value => $label) {
            $options[] = compact('value', 'label');
        }

        return $options;
    }

    private function options(): array
    {
        return [
            self::UP => new Phrase('Up (round away from zero)'),
            self::CEILING => new Phrase('Ceiling (round towards positive infinity)'),
            self::DOWN => new Phrase('Down (round towards zero)'),
            self::FLOOR => new Phrase('Floor (round towards negative infinity)'),
            self::HALFDOWN => new Phrase('Half Down (round towards "nearest neighbor" unless both neighbors are equidistant, in which case round down)'),
            self::HALFEVEN => new Phrase('Half Even (round towards "nearest neighbor" unless both neighbors are equidistant, in which case round towards the even neighbor)'),
            self::HALFUP => new Phrase('Half Up (round towards "nearest neighbor" unless both neighbors are equidistant, in which case round up)'),
        ];
    }
}
