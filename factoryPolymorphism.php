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