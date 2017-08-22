<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/22
 * Time: 上午11:40
 */

class BadDollar
{
    protected $amount;
    public function __construct($amount = 0)
    {
        $this->amount = (float)$amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function add($dollar)
    {
        $this->amount += $dollar->getAmount();
    }
}

class Work
{
    protected $salary;
    public function __construct()
    {
        $this->salary = new BadDollar(200);
    }

    public function payDay()
    {
        return $this->salary;
    }
}

class Person
{
    public  $wallet;
}

class valueObjectTest extends PHPUnit_Framework_TestCase
{
    public function testBadDollarWorking()
    {
        $job = new Work();
        $p1 = new Person();
        $p2 = new Person();
        $p1->wallet = $job->payDay();
        $this->assertEquals(200, $p1->wallet->getAmount());

        $p2->wallet = $job->payDay();
        $this->assertEquals(200, $p2->wallet->getAmount());

        $p1->wallet->add($job->payDay());
        $this->assertEquals(400, $p1->wallet->getAmount());
        $this->assertEquals(200, $p2->wallet->getAmount());
        $this->assertEquals(200, $job->payDay()->getAmount());
    }
}