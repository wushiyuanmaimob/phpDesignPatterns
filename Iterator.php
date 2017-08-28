<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/28
 * Time: 上午9:51
 */
//在不需要了解内部实现的前提下，遍历一个聚合对象的内部元素而又不暴露改对象的内部表示，这就是PHP迭代器模式的定义。

//使用场景：
//访问一个聚合对象的内容而无需暴露它的内部表示
//支持对聚合对象的多种遍历
//为遍历不同的聚合对象提供了一个统一的接口

class ConcreteIterator implements Iterator
{
    private $position = 0;
    private $arr;

    public function __construct(array $arr)
    {
        $this->arr = $arr;
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
        $this->position = 0;
    }

    public function current()
    {
        // TODO: Implement current() method.
        return $this->arr[$this->position];
    }

    public function key()
    {
        // TODO: Implement key() method.
        return $this->position;
    }

    public function next()
    {
        // TODO: Implement next() method.
        return ++$this->position;
    }

    public function valid()
    {
        // TODO: Implement valid() method.
        return isset($this->arr[$this->position]);
    }
}

$arr = ['xiao hong', 'xiao ming', 'xiao hua'];
$concreteIterator = new ConcreteIterator($arr);

$current = $concreteIterator->current();
var_dump($current);
var_dump($concreteIterator->next());
var_dump($concreteIterator->current());
var_dump($concreteIterator->valid());