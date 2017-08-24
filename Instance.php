<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/24
 * Time: 下午1:40
 */

//几乎所有面向对象的程序中，总有一两个资源被创建出来，在程序中持续被共享使用。例如，在一个电子商务程序的数据库连接中使用：这个连接在应用程序启动时初始化，程序于是可以有效的执行；
//当程序结束时，这个连接最终被断开并且销毁。如果是你写代码，没必要在每时每刻创建一个数据库连接，这样非常低效。。 已 经建立好的 连接应该能被你的代码简单重复的使用。 这 个问题就是， 基 于以上要求你将如何进行这个数 据库连接?(或者连接其它被循环使用的唯一资源，比如一个开放文件或者一个队列。

//问题：如何确保一个特殊类的实例是独一无二的（它是这个类的唯一实例），并且它很容易存储。


class DbConn
{
    static $instance = NULL;

    private function __construct()
    {
    }

    public function getInstance()
    {
        if(!DbConn::$instance) {
            DbConn::$instance = new DbConn();
        }
        return DbConn::$instance;
    }
}

//
class ApplicationConfig
{
    public $_state;
    public function __construct()
    {
        $key = '__stealth_singleton_state_index__';
        if(!(array_key_exists($key, $GLOBALS)) || !is_array($GLOBALS[$key])) {
            $GLOBALS[$key] = [];
        }
        $this->_state = &$GLOBALS[$key];
    }

    public function set($key, $val)
    {
        $this->_state[$key] = $val;
    }

    public function get($key)
    {
        if(array_key_exists($key, $this->_state))
        {
            return $this->_state[$key];
        }
    }
}


class testInstance extends PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $this->assertInstanceOf('DbConn', $obj1 = DbConn::getInstance(), 'The returned object is not an instance of DbConn');
        $this->assertSame($obj1, $obj2 = DbConn::getInstance(), 'Two calls to getInstance() not return the same object');
    }

    public function testApplConfig()
    {
        $this->assertInstanceOf('ApplicationConfig', $obj1 = new ApplicationConfig());
        $this->assertInstanceOf('ApplicationConfig', $obj2 = new ApplicationConfig());

        $testValue = '/path/to/cache' . rand(1, 100);
        $obj1->set('cache_path', $testValue);
        $this->assertEquals($testValue, $obj2->get('cache_path'));
    }
}
