<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/22
 * Time: 下午5:22
 */
namespace phpDesignPatterns\factory;

//工厂模式的任务就是把对象的创建过程都封装起来，然后返回一个所需要的新对象。

//以下是反例
define('DB_USER', 'daemon');
define('DB_PW', 'yuanbaba');
define('DB_NAME', 'pluto');

class MySqlConnection
{
    public function __construct()
    {

    }
}

class Product
{
    function getList()
    {
        $db = new MySqlConnection(DB_USER, DB_PW, DB_NAME);
    }

    function getByName($name)
    {
        $db = new MySqlConnection(DB_USER, DB_PW, DB_NAME);
    }
}
//这样做为什么不好：
//1. 你可以轻松地改变连接数据库的参数，但你不能增加或改变这些参数地顺序，除非你把 所有连接代码都改了。
//2. 你不能轻松的实例化一个新类去连接另一种数据库，比如说PostgresqlConnection。
//3. 这样很难单独测试和证实连接对象的状态。

//使用工厂模式：
class ProductFactory
{
    public function getList()
    {
        $db = $this->_getConnection();
    }

    public function _getConnection()
    {
        return new MySqlConnection(DB_USER, DB_PW, DB_NAME);    //这就是连接mysql数据库功能的一个工厂
    }
}

//下面是工厂的另一种变化，你静态的调用一个工厂类
class ProductFactoryStatic
{
    public function getList()
    {
        $db = DbConnectionBroker::getConnection();
    }
}

class DbConnectionBroker
{
    public static function getConnection()
    {
        return new MySqlConnection(DB_USER, DB_PW, DB_NAME);
    }
}
//这里DbConnectionBroker::getConnection()产生的效果和前面的一样 ,但这样却很有 好处: 我们不必在每个需要连接数据库的类中加入调用 new MysqlConnection(DB_USER， DB_PW,DB_NAME)的方法。

//另一种变化就是引用一个外部工厂对象的资源，和这个对象定义了数据库连接的参数:

class ProductSource
{
    var $_db_maker;

    public function setDbFactory($connection_factory)
    {
        $this->_db_maker = $connection_factory;
    }

    public function getList()
    {
        $db = $this->_db_maker->getConnection();
    }
}

interface Db
{
    public function getConnection();
}

class DbMysql implements Db
{
    public function getConnection()
    {
    }
}