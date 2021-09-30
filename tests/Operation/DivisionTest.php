<?php
/**
 * This file is part of the PrestaShop\Decimal package
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace PrestaShop\Decimal\Test\Operation;

use Generator;
use PHPUnit\Framework\TestCase;
use PrestaShop\Decimal\DecimalNumber;
use PrestaShop\Decimal\Exception\DivisionByZeroException;
use PrestaShop\Decimal\Operation\Division;

class DivisionTest extends TestCase
{
    /**
     * Given two decimal numbers
     * When computing the division operation between them
     * Then we should get the result of dividing number1 by number2
     *
     * @param string $number1
     * @param string $number2
     * @param string $expectedResult
     *
     * @dataProvider provideNumbersToDivide
     */
    public function testItDividesNumbers($number1, $number2, $expectedResult)
    {
        $n1 = new DecimalNumber($number1);
        $n2 = new DecimalNumber($number2);

        $operation = new Division();
        $result1 = $operation->computeUsingBcMath($n1, $n2, 20);
        $result2 = $operation->computeWithoutBcMath($n1, $n2, 20);

        $this->assertSame($expectedResult, (string) $result1, "Failed asserting $number1 / $number2 = $expectedResult (BC Math)");
        $this->assertSame($expectedResult, (string) $result2, "Failed asserting $number1 / $number2 = $expectedResult");
    }

    /**
     * Given two decimal numbers
     * When computing the division operation between them
     * Then we should get the result of dividing number1 by number2
     *
     * @param string $number1
     * @param string $number2
     * @param string $expectedResult
     *
     * @dataProvider provideNumbersToDivideWithPrecision
     */
    public function testItDividesNumbersToPrecision(string $number1, string $number2, int $precision, string $expectedResult): void
    {
        $n1 = new DecimalNumber($number1);
        $n2 = new DecimalNumber($number2);

        $operation = new Division();
        $result1 = $operation->computeUsingBcMath($n1, $n2, $precision);
        $result2 = $operation->computeWithoutBcMath($n1, $n2, $precision);

        $this->assertSame($expectedResult, (string) $result1, "Failed asserting $number1 / $number2 = $expectedResult (BC Math)");
        $this->assertSame($expectedResult, (string) $result2, "Failed asserting $number1 / $number2 = $expectedResult");
    }

    /**
     * Given a decimal number which is not zero
     * When trying to divide it by zero using BC Math
     * Then we should get a DivisionByZeroException
     */
    public function testDivisionByZeroUsingBcMathThrowsException()
    {
        $this->expectException(DivisionByZeroException::class);

        (new Division())->computeUsingBcMath(
            new DecimalNumber('1'),
            new DecimalNumber('0')
        );
    }

    /**
     * Given a decimal number which is not zero
     * When trying to divide it by zero without BC Math
     * Then we should get a DivisionByZeroException
     */
    public function testDivisionByZeroWithoutBcMathThrowsException()
    {
        $this->expectException(DivisionByZeroException::class);

        (new Division())->computeWithoutBcMath(
            new DecimalNumber('1'),
            new DecimalNumber('0')
        );
    }

    public function provideNumbersToDivide()
    {
        return [
            // 0 as dividend should always yield 0
            ['0', '1', '0'],
            ['0', '1123234.4234234123', '0'],
            ['0', '-1', '0'],
            ['0', '-1123234.4234234123', '0'],
            // 1 as divisor should always yield the dividend
            ['1', '1', '1'],
            ['13524.2342342347262', '1', '13524.2342342347262'],
            // -1 should always yield the inverted dividend
            ['1', '-1', '-1'],
            ['13524.2342342347262', '-1', '-13524.2342342347262'],
            // integer results
            ['2', '1', '2'],
            ['2', '2', '1'],
            ['99', '99', '1'],
            ['198', '99', '2'],
            ['990', '99', '10'],
            ['2', '-1', '-2'],
            ['2', '-2', '-1'],
            ['99', '-99', '-1'],
            ['198', '-99', '-2'],
            ['990', '-99', '-10'],
            ['-2', '-1', '2'],
            ['-2', '-2', '1'],
            ['-99', '-99', '1'],
            ['-198', '-99', '2'],
            ['-990', '-99', '10'],
            ['-2', '1', '-2'],
            ['-2', '2', '-1'],
            ['-99', '99', '-1'],
            ['-198', '99', '-2'],
            ['-990', '99', '-10'],
            // decimal results
            ['1', '100', '0.01'],
            ['1', '3', '0.33333333333333333333'],
            ['1231415', '77', '15992.4025974025974025974'],
            // decimal dividend
            ['12315.73452342341', '27', '456.13831568234851851851'],
            ['0.73452342341', '27', '0.0272045712374074074'],
            ['8.333333333333333', '1.333333333333333', '6.2500000000000013125'],
            // decimal divisor
            ['27', '12315.73452342341', '0.00219231747393129081'],
            ['27', '0.00000012315', '219244823.38611449451887941534'],
        ];
    }

    public function provideNumbersToDivideWithPrecision(): Generator
    {
        yield ['10.00', '1.2', 6, '8.333333'];
        yield ['10.00', '1.1643', 4, '8.5888'];
        yield ['8.333333333', '1.1643', 4, '7.1573'];
        yield ['8.333333333', '1.1643', 6, '7.157376'];
        yield ['8.333333', '1.1643', 9, '7.157376105'];
        yield ['8.333333333', '1.1643', 12, '7.157376391823'];
        yield ['123456789123456788999999', '1', 12, '123456789123456788999999'];
    }
}
