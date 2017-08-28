<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/28
 * Time: 上午11:06
 */

//观察者
interface ITicketObserver
{
    function onBuyTicketOver($sender, $args);
}

//被观察者
interface ITicketObservable
{
    function addObserver($observer);
}

class HipiaoBuy implements ITicketObservable
{
    private $_observers = [];

    public function buyTicket($ticket)
    {

        //TODO购票逻辑
        foreach($this->_observers as $obs) {
            $obs->onBuyTicketOver($this, $ticket);
        }
    }

    public function addObserver($observer)
    {
        // TODO: Implement addObserver() method.
        $this->_observers[] = $observer;
    }
}

class HipiaoMSM implements ITicketObserver
{
    public function onBuyTicketOver($sender, $args)
    {
        // TODO: Implement onBuyTicketOver() method.
        echo (date('Y-m-d H:i:s')) . "短信日志记录：购票成功：$args\n";
    }
}

class HipiaoTxt implements ITicketObserver
{
    public function onBuyTicketOver($sender, $args)
    {
        // TODO: Implement onBuyTicketOver() method.
        echo (date('Y-m-d H:i:s')) . "文本日志记录：购票成功：$args\n";
    }
}

class HipiaoDiKou implements ITicketObserver
{
    public function onBuyTicketOver($sender, $args)
    {
        // TODO: Implement onBuyTicketOver() method.
        echo (date('Y-m-d H:i:s')) . "赠送抵扣券：购票成功：$args 赠送10元抵扣券1张，\n";
    }
}

class HipiaoThanks implements ITicketObserver
{
    public function onBuyTicketOver($sender, $args)
    {
        // TODO: Implement onBuyTicketOver() method.
        echo (date('Y-m-d H:i:s')) . "购票成功，感谢！\n";
    }
}

$buy = new HipiaoBuy();
$buy->addObserver(new HipiaoMSM());
$buy->addObserver(new HipiaoTxt());
$buy->addObserver(new HipiaoDiKou());
$buy->addObserver(new HipiaoThanks());

$buy->buyTicket("一排一号");