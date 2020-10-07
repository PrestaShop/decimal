<?php
/**
 * This file is part of the PrestaShop\Decimal package
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace PrestaShop\Decimal\Operation;

use PrestaShop\Decimal\Exception\DivisionByZeroException;
use PrestaShop\Decimal\DecimalNumber;

/**
 * Computes the division between two decimal numbers.
 */
class Division
{
    const DEFAULT_PRECISION = 6;

    /**
     * Performs the division.
     *
     * A target maximum precision is required in order to handle potential infinite number of decimals
     * (e.g. 1/3 = 0.3333333...).
     *
     * If the division yields more decimal positions than the requested precision,
     * the remaining decimals are truncated, with **no rounding**.
     *
     * @param DecimalNumber $a Dividend
     * @param DecimalNumber $b Divisor
     * @param int $precision Maximum decimal precision
     *
     * @return DecimalNumber Result of the division
     * @throws DivisionByZeroException
     */
    public function compute(DecimalNumber $a, DecimalNumber $b, $precision = self::DEFAULT_PRECISION)
    {
        if (function_exists('bcdiv')) {
            return $this->computeUsingBcMath($a, $b, $precision);
        }

        return $this->computeWithoutBcMath($a, $b, $precision);
    }

    /**
     * Performs the division using BC Math
     *
     * @param DecimalNumber $a Dividend
     * @param DecimalNumber $b Divisor
     * @param int $precision Maximum decimal precision
     *
     * @return DecimalNumber Result of the division
     * @throws DivisionByZeroException
     */
    public function computeUsingBcMath(DecimalNumber $a, DecimalNumber $b, $precision = self::DEFAULT_PRECISION)
    {
        if ((string) $b === '0') {
            throw new DivisionByZeroException();
        }

        return new DecimalNumber((string) bcdiv($a, $b, $precision));
    }

    /**
     * Performs the division without BC Math
     *
     * @param DecimalNumber $a Dividend
     * @param DecimalNumber $b Divisor
     * @param int $precision Maximum decimal precision
     *
     * @return DecimalNumber Result of the division
     * @throws DivisionByZeroException
     */
    public function computeWithoutBcMath(DecimalNumber $a, DecimalNumber $b, $precision = self::DEFAULT_PRECISION)
    {
        $bString = (string) $b;
        if ('0' === $bString) {
            throw new DivisionByZeroException();
        }

        $aString = (string) $a;

        // 0 as dividend always yields 0
        if ('0' === $aString) {
            return $a;
        }

        // 1 as divisor always yields the dividend
        if ('1' === $bString) {
            return $a;
        }

        // -1 as divisor always yields the the inverted dividend
        if ('-1' === $bString) {
            return $a->invert();
        }

        // if dividend and divisor are equal, the result is always 1
        if ($a->equals($b)) {
            return new DecimalNumber('1');
        }

        $aPrecision = $a->getPrecision();
        $bPrecision = $b->getPrecision();

        $maxPrecision = max($aPrecision, $bPrecision);

        if ($maxPrecision > 0) {
            // make $a and $b integers by multiplying both by 10^(maximum number of decimals)
            $a = $a->toMagnitude($maxPrecision);
            $b = $b->toMagnitude($maxPrecision);
        }

        $result = $this->integerDivision($a, $b, max($precision, $aPrecision));

        return $result;
    }

    /**
     * Computes the division between two integer DecimalNumbers
     *
     * @param DecimalNumber $a Dividend
     * @param DecimalNumber $b Divisor
     * @param int $precision Maximum number of decimals to try
     *
     * @return DecimalNumber
     */
    private function integerDivision(DecimalNumber $a, DecimalNumber $b, $precision)
    {
        $dividend = $a->getCoefficient();
        $divisor = new DecimalNumber($b->getCoefficient());
        $dividendLength = strlen($dividend);
        $result = '';
        $exponent = 0;

        $currentSequence = '';
        for ($i = 0; $i < $dividendLength; $i++) {
            // append digits until we get a number big enough to divide
            $currentSequence .= $dividend[$i];

            if ($currentSequence < $divisor) {
                if (!empty($result)) {
                    $result .= '0';
                }
            } else {
                // subtract divisor as many times as we can
                $remainder = new DecimalNumber($currentSequence);
                $multiple = 0;
                do {
                    $multiple++;
                    $remainder = $remainder->minus($divisor);
                } while ($remainder->isGreaterOrEqualThan($divisor));

                $result .= (string) $multiple;

                // reset sequence to the reminder
                $currentSequence = (string) $remainder;
            }

            // add up to $precision decimals
            if ($currentSequence > 0 && $i === $dividendLength - 1 && $precision > 0) {
                // "borrow" up to $precision digits
                --$precision;
                $dividend .= '0';
                $dividendLength++;
                $exponent++;
            }
        }

        $sign = ($a->isNegative() xor $b->isNegative()) ? '-' : '';

        return new DecimalNumber($sign . $result, $exponent);
    }
}
