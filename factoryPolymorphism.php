<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/23
 * Time: 下午4:41
 */

abstract class Property
{
    protected $name;
    protected $price;
    protected $game;
    protected $owner;

    function __construct($name, $price, $game)
    {
        $this->name = $name;
        $this->price = new \phpDesignPatterns\valueObjectTuning\Dollar($price);
        $this->game = $game;
    }

    abstract protected function calcRent();

    public function purchase($player)
    {
        $player->pay($this->price);
        $this->owner = $player;
    }

    public function rent($player)
    {
        if($this->owner && $this->owner != $player) {
            $this->owner->collect($player($this->calcRent()));
        }
    }
}

class Street extends Property
{
    protected $base_rent;
    public $color;

    public function setRent($rent)
    {
        $this->base_rent = new \phpDesignPatterns\valueObjectTuning\Dollar($rent);
    }

    protected function calcRent()
    {
        if($this->game->hasMonopoly($this->owner, $this->color)) {
            return $this->base_rent->add($this->base_rent);
        }
        return $this->base_rent;
    }
}

class RailRoad extends Property
{
    protected function calcRent()
    {
        switch ($this->game->railRoadCount($this->owner)) {
            case 1:
                return new \phpDesignPatterns\valueObjectTuning\Dollar(25);
            case 2:
                return new \phpDesignPatterns\valueObjectTuning\Dollar(50);
            case 3:
                return new \phpDesignPatterns\valueObjectTuning\Dollar(100);
            case 4:
                return new \phpDesignPatterns\valueObjectTuning\Dollar(200);
            default:
                return new \phpDesignPatterns\valueObjectTuning\Dollar();
        }
    }
}

class Utility extends Property
{
    protected function calcRent()
    {
        switch (!$this->game->utilityCount($this->owner)) {
            case 1:
                return new \phpDesignPatterns\valueObjectTuning\Dollar(4 * $this->game->lastRoll());
            case 2:
                return new \phpDesignPatterns\valueObjectTuning\Dollar(10 * $this->game->lastRoll());

            default:
                return new \phpDesignPatterns\valueObjectTuning\Dollar();
        }
    }
}

class PropertyInfo
{
    const TYPE_KEY = 0;
    const PRICE_KEY = 1;
    const COLOR_KEY = 2;
    const RENT_KEY = 3;

    public $type;
    public $price;
    public $color;
    public $rent;

    public function __construct($props)
    {
        $this->type = $this->propValue($props, 'type', self::TYPE_KEY);
        $this->price = $this->propValue($props, 'price', self::PRICE_KEY);
        $this->color = $this->propValue($props, 'color', self::COLOR_KEY);
        $this->rent = $this->propValue($props, 'rent', self::RENT_KEY);
    }

    protected function propValue($props, $prop, $key)
    {
        if(array_key_exists($key, $props)) {
            return $this->$prop = $props[$key];
        }
    }
}

class Assessor
{
    protected $game;
    protected $prop_info = [
        'Mediterranean Ave.'    => ['Street', 60, 'Purple', 2],
        'Baltic Ave'    => ['Street', 60, 'Purple', 2]
    ];
    public function setGame($game)
    {
        $this->game = $game;
    }
    public function getProperty($name)
    {
        $prop_info = $this->getPropInfo($name);
        switch ($prop_info->type) {
            case 'Street':
                $prop = new Street($this->game, $name, $prop_info->price);
                $prop->color = $prop_info->color;
                $prop->setRent($prop_info->rent);
                return $prop;
                break;
            case 'RailRoad':
                return new RailRoad($this->game, $name, $prop_info->price);
                break;
            case 'Utility':
                return new Utility($this->game, $name, $prop_info->price);
                braek;
        }
    }

    protected function getPropInfo($name)
    {
        try {
            if (!array_key_exists($name, $this->prop_info)) {
                throw new InvalidPropertyNameException($name);
            }
            return new PropertyInfo($this->prop_info[$name]);
        }catch (InvalidPropertyNameException $e) {
            echo '【' . $e->getCode() . '】' . $e->getMessage();
            exit();
        }
    }
}

class InvalidPropertyNameException extends Exception
{

}

class testFactoryPolymorphism extends PHPUnit_Framework_TestCase
{
    public function testPropertyInfo()
    {
        $list = ['type', 'price', 'color', 'rent'];
        $this->assertInstanceOf('PropertyInfo', $testprop = new PropertyInfo($list));
        foreach ($list as $prop) {
            $this->assertEquals($prop, $testprop->$prop);
        }
    }

    public function testPropertyInfoMissingColorRent()
    {
        $list = ['type', 'price'];
        $this->assertInstanceOf('PropertyInfo', $testprop = new PropertyInfo($list));
        foreach ($list as $prop) {
            $this->assertEquals($prop, $testprop->$prop);
        }
        $this->assertNull($testprop->color);
        $this->assertNull($testprop->color);
    }

    public function testAssessor()
    {
        $assessor = new Assessor();
        $assessor->getProperty('qBall');
    }
}
