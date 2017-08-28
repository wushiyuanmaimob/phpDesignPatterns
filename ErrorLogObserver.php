<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/28
 * Time: 上午11:41
 */
//记录错误的句柄
//默认的错误日志记录句柄只会在屏幕上显示一些出错信息
//增强后的句柄还可以将出错的信息写进一个日志文件||写进系统日志||通过电子邮件发送出去||或利用声音报告出错误信息
//可以构造出一个有级别的报错方案，只允许想向那些已经为具体得出错信息注册过的观测者报告。从一般的警告信息到想数据库宕机之类严重的出错信息都可以报告。

interface IErrorHandler
{
    public function attach($observer);
}

interface Log
{
    public function doLog($mess);
}

class ErrorHandler implements IErrorHandler
{
    private static $errorHandler;
    private $_observers = [];

    private function __construct()
    {
    }

    public static function getErrorHandlerInstance()
    {
        if(!self::$errorHandler instanceof ErrorHandler) {
            self::$errorHandler = new self();
        }
        return self::$errorHandler;
    }

    public function attach($observer)
    {
        // TODO: Implement attach() method.
        $this->_observers[] = $observer;
    }

    public function log($mess)
    {
        foreach ($this->_observers as $obs) {
            $obs->doLog($mess);
        }
    }
}

class EmailErrorLogger implements Log
{
    public function doLog($mess)
    {
        // TODO: Implement doLog() method.
        echo "邮件通知：" . $mess . "\n";
    }
}

class FileErrorLogger implements Log
{
    public function doLog($mess)
    {
        // TODO: Implement doLog() method.
        echo "文件记录：" . $mess . "\n";
    }
}

$eh = ErrorHandler::getErrorHandlerInstance();
$eh->attach(new EmailErrorLogger('shiyuan.wu@maimob.cn'));
$eh->log('别和我开玩笑！');


