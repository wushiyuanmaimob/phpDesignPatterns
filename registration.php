<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/25
 * Time: 上午10:57
 */

class MySqlConnection
{
    public function __construct()
    {

    }
}

class Registry
{
    private static $instance;
    private $_store = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(!Registry::$instance instanceof Registry) {
            Registry::$instance = new Registry();
        }
        return Registry::$instance;
    }

    public function isValid($key)
    {
        return array_key_exists($key, $this->_store);
    }

    public function get($key)
    {
        if(array_key_exists($key, $this->_store)) {
            return $this->_store[$key];
        }
    }

    public function set($key, $obj)
    {
        $this->_store[$key] = $obj;
    }
}

//实例：
class DbConnections extends Registry {

}

//domain model classes
class Customer
{
    public $db;
    public function customer()
    {
        $dbc = DbConnections::getInstance();
        $this->db = $dbc->get('contacts');
    }
}

class Orders
{
    public $db_cur;
    public $db_hist;

    public function contact()
    {
        $dbc = DbConnections::getInstance();
        $this->db_cur = $dbc->get('orders');
        $this->db_hist = $dbc->get('archive');
    }
}

define('REGISTRY_GLOBAL_STORE', '__registry_global_store_key__');
class RegistryGlobal
{
    private $_store = [];

    public function __construct()
    {
        if(!array_key_exists(REGISTRY_GLOBAL_STORE, $GLOBALS) || !is_array($GLOBALS[REGISTRY_GLOBAL_STORE])) {
            $GLOBALS[REGISTRY_GLOBAL_STORE] = [];
        }
        $this->_store[] = &$GLOBALS[REGISTRY_GLOBAL_STORE];
    }

    public function isValid($key)
    {
        return array_key_exists($key, $this->_store);
    }

    public function get($key)
    {
        if(array_key_exists($key, $this->_store)) {
            return $this->_store[$key];
        }
    }

    public function set($key, $obj)
    {
        $this->_store[$key] = $obj;
    }
}

class AddressBook
{
    public $registry;

    public function __construct()
    {
        $this->registry = Registry::getInstance();
    }

    public function findById($id)
    {
        if(!$this->registry->isValid($id)) {
            $this->registry->set($id, new Contact($id));
        }
        return $this->registry->get($id);
    }
}

class Contact
{
    public function contact($id)
    {

    }
}

class testRegistration extends PHPUnit_Framework_TestCase
{
    public function testRegistryIsSingleton()
    {
        $this->assertInstanceOf('Registry', $reg = Registry::getInstance(), "not Registry");
        $this->assertSame($reg, Registry::getInstance());
    }

    //一个注册模式应该提供get()和set()方法来存储和取得对象（用一些属性key）而且也应该提供一个isValid()方法来确定一个给定的属性是否已经设置。
    public function testEmptyRegistryKeyIsInvalid()
    {
        $reg = Registry::getInstance();
        $this->assertFalse($reg->isValid('key'));
    }

    public function testEmptyRegistryKeyReturnsNull()
    {
        $reg = Registry::getInstance();
        $this->assertNull($reg->get('key'));
    }

    public function testSetRegistryKeyBecomesValid()
    {
        $reg = Registry::getInstance();
        $testValue = 'something';
        $reg->set('key', $testValue);
        $this->assertTrue($reg->isValid('key'));
        $this->assertEquals($testValue, $reg->get('key'));
    }

    public function testSetRegistryValuesReference()
    {
        $reg = Registry::getInstance();
        $testValue = 'something';
        $reg->set('key', $testValue);
        $this->assertEquals($testValue, $reg->get('key'));
        $testValue .= ' else';
        $reg->set('key', $testValue);
        $this->assertEquals('something else', $reg->get('key'));
    }

    public function testDbConnections()
    {
        $abc = DbConnections::getInstance();
        $abc->set('contacts', new MySqlConnection('user1', 'pass1', 'db1', 'host1'));
        $this->assertInstanceOf('MySqlConnection', $abc->get('contacts'));
        $abc->set('orders', new MySqlConnection('user2', 'pass2', 'db2', 'host2'));
        $this->assertInstanceOf('MysqlConnection', $abc->get('orders'));
        $abc->set('archives', new MySqlConnection('user3', 'pass3', 'db3', 'host3'));
    }

    public function testRegistryGlobal()
    {
        $reg = new RegistryGlobal();
        $this->assertFalse($reg->isValid('key'));
        $this->assertNull($reg->get('key'));
        $testValue = 'something';
        $reg->set('key', $testValue);
    }

    public function testRegistryGlobalMonoState()
    {
        $reg = new RegistryGlobal();
        $reg2 = new RegistryGlobal();
//        $this->assertSame($reg, $reg2);
        $testValue = 'something';
        $reg->set('test', $testValue);
//        $this->assertSame($reg->get('test'), $reg2->get('test'));
    }

//    public function testRegistryMonoState()
//    {
//        $this->assertSame($reg = new RegistryMonoState(), $reg2 = new RegistryMonoState());
//        $this->assertFalse($reg->isValid('key'));
//        $this->assertNull($reg->get('key'));
//        $testValue = 'something';
//        $reg->set('key', $testValue);
//        $this->assertSame($reg->get('key'), $reg2->get('key'));
//    }

}

