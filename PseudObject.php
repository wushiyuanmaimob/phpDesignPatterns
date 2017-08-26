<?php
/**
 * Created by PhpStorm.
 * User: wushiyuan
 * Date: 17-8-26
 * Time: 上午10:56
 */

class Accumulator
{
    private $total;

    public function add($item)
    {
        $this->total += $item;
    }

    public function total()
    {
        return $this->total;
    }
}

function calc_total($items, $sum)
{
    foreach ($items as $item)
    {
        $sum->add($item);
    }
}

class MockObjectTestCase extends PHPUnit_Framework_TestCase
{
    public function testCalcTotal()
    {
        $sum = new Accumulator();
        calc_total([1, 2, 3], $sum);

        $this->assertEquals(6, $sum->total());
    }

    public function testCalcTax()
    {
        $amount = new MockAccumulator($this);
    }
}