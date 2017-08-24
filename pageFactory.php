<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/24
 * Time: 下午1:18
 */
//迟加载（Lazy loading）的工厂

//使用工厂的另一个好处就是它具有迟加载的能力，这种情况常被用在：一个工厂中包含很多子类，这些子类被定义在单独的文件中。
//在迟加载模式中是不预加载所有的操作（像包含php文件或者执行数据库查询语句），除非脚本中声明要加载。

class pageFactory
{
    public function getPage()
    {
        $page = (array_key_exists('page', $_REQUEST)) ? strtolower($_REQUEST['page']) : '';
        switch ($page) {
            case 'entry' : $pageClass = 'Detail'; break;
            case 'edit'  : $pageClass = 'Edit'; break;
            case 'commit': $pageClass = 'Commit'; break;
            default:
                $pageClass = 'Index';
        }
        if(!class_exists($pageClass)) {
            require_once 'pages/' . $pageClass . '.php';
        }
        return new $pageClass;
    }
}