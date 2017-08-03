<?php
/**
 * This file is part of the PrestaShop\Decimal package
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace PrestaShop\Decimal\Test\Operation;

use PrestaShop\Decimal\Number;
use PrestaShop\Decimal\Operation\Comparison;

class ComparisonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Given two numbers
     * When comparing them
     * Then we should get
     * - 1 if a > b
     * - -1 if b > a
     * - 0 if a == b
     *
     * @param string $a
     * @param string $b
     * @param int $expected
     *
     * @dataProvider provideCompareTestCases
     */
    public function testItComparesNumbers($a, $b, $expected)
    {
        $comparison = new Comparison();

        $result1 = $comparison->compareUsingBcMath(new Number($a), new Number($b));
        $result2 = $comparison->compareWithoutBcMath(new Number($a), new Number($b));

        $this->assertSame($expected, $result1, "Failed assertion (BC Math)");
        $this->assertSame($expected, $result2, "Failed assertion");
    }

    public function provideCompareTestCases()
    {
        return [
            // a is greater
            'greater 1'  => ['1', '0', 1],
            'greater 2'  => ['1.0', '0', 1],
            'greater 3'  => ['1.01', '1.0', 1],
            'greater 4'  => ['1.0000000000000000000000001', '1.0', 1],
            'greater 5'  => ['10', '001', 1],
            'greater 6'  => ['10', '-10', 1],
            'greater 7'  => ['10', '-100', 1],
            'greater 8'  => ['100', '10', 1],
            'greater 9'  => ['-1', '-2', 1],
            'greater 10' => ['-1', '-0000002', 1],
            'greater 11' => ['-1', '-1.0000000001', 1],
            // a is equal
            'equal 1'    => ['1', '01', 0],
            'equal 2'    => ['0.1', '0000.1000000000000', 0],
            // a is lower
            'lower 1'    => ['0', '1', -1],
            'lower 2'    => ['-1', '0', -1],
            'lower 3'    => ['-1', '0.0001', -1],
            'lower 4'    => ['-2', '-1', -1],
            'lower 5'    => ['-02', '-1', -1],
            'lower 6'    => ['-2', '-01', -1],
            'lower 8'    => ['10', '100', -1],
            'lower 9'    => ['-1.000001', '-1', -1],
            'lower 10'   => ['-1000.000001', '-10.0001', -1],
        ];
    }

}
