<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/22
 * Time: 下午1:32
 */

class Dollar
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

    public function add($dollar) {
        return new Dollar($this->amount + $dollar->getAmount());
    }
    //简单来说，在PHP5里面使用价值设计模式时，需要注意以下几个方面: 1.保护值对象的属性，禁止被直接访问。2.在构造函数中就对属性进行赋值。 3.去掉任何一个会改变属性值的方式函数(setter) ，否则属性值很容易被改变

    public function debit($dollar)
    {
        return new Dollar($this->amount - $dollar->getAmount());
    }

    public function divide($divisor)
    {
        return array_fill(0, $divisor, new Dollar($this->amount / $divisor));
    }
}

class Monopoly
{
    protected $go_amount;

    public function __construct()
    {
        $this->go_amount = new Dollar(200);
    }

    public function passGo($player)
    {
        $player->collect($this->go_amount);
    }

    public function payRent($from, $to, $rent)
    {
        $to->collect($from->pay($rent));
    }
}

class Player
{
    protected $name;
    protected $savings;

    public function __construct($name)
    {
        $this->name = $name;
        $this->savings = new Dollar(1500);
    }

    public function collect($amount)
    {
        $this->savings = $this->savings->add($amount);
    }

    public function pay($amount)
    {
        $this->savings = $this->savings->debit($amount);
        return $amount;
    }

    public function getBalance()
    {
        return $this->savings->getAmount();
    }
}

class valueObjectTuningTest extends PHPUnit_Framework_TestCase
{
    public function testGame()
    {
        $game = new Monopoly();
        $player1 = new Player('daemon');
        $this->assertEquals(1500, $player1->getBalance());
        $game->passGo($player1);
        $this->assertEquals(1700, $player1->getBalance());
        $game->passGo($player1);
        $this->assertEquals(1900, $player1->getBalance());
    }

    public function testRent()
    {
        $game = new Monopoly();
        $player1 = new Player('Madeline');
        $player2 = new Player('Caleb');
        $this->assertEquals(1500, $player1->getBalance());
        $this->assertEquals(1500, $player2->getBalance());

        $game->payRent($player1, $player2, new Dollar(26));

        $this->assertEquals(1474, $player1->getBalance());
        $this->assertEquals(1526, $player2->getBalance());
    }

    public function testDollarDivideReturnArrayOfDivisorSize()
    {
        $fullAmount = new Dollar(8);
        $parts = 4;
        $result = $fullAmount->divide($parts);
        $this->assertEquals($parts, count($result));
    }

    public function testDollarDrivesEquallyForExactMultiple()
    {
        $test_amount = 1.25;
        $parts = 4;
        $dollar = new Dollar($test_amount * $parts);
        foreach ($dollar->divide($parts) as $part) {
            $this->assertInstanceOf('Dollar', $part);
            $this->assertEquals($test_amount, $part->getAmount());
        }
    }
}