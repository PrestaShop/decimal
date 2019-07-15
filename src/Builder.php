<?php
/**
 * This file is part of the PrestaShop\Decimal package
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace PrestaShop\Decimal;

use PrestaShop\Decimal\Number;


/**
 * Builds Number instances
 */
class Builder
{

    const NUMBER_PATTERN = "/^(?<sign>[-+])?(?<integerPart>\d+)(?:\.(?<fractionalPart>\d+))?(?<exponentPart>[eE](?<exponentSign>[-+])(?<exponent>\d+))?$/";

    /**
     * Builds a Number from a string
     *
     * @param string $number
     *
     * @return Number
     */
    public static function parseNumber($number)
    {
        if (!preg_match(self::NUMBER_PATTERN, $number, $parts)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" cannot be interpreted as a number', print_r($number, true))
            );
        }

        // extract the integer part and remove leading zeroes and plus sign
        $integerPart = ltrim($parts['integerPart'], '0');

        $fractionalPart = '';
        if (array_key_exists('fractionalPart', $parts)) {
            // extract the fractional part and remove trailing zeroes
            $fractionalPart = rtrim($parts['fractionalPart'], '0');
        }

        $exponent = strlen($fractionalPart);
        $coefficient = $integerPart . $fractionalPart;

        // when coefficient is '0' or a sequence of '0'
        if ('' === $coefficient) {
            $coefficient = '0';
        }

        return new Number($parts['sign'] . $coefficient, $exponent);
    }

}
