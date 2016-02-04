<?php namespace App\Lib\Orm;

use App\Lib\MonologSQLLogger;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Spot\Entity;

abstract class EntityManager
{
    protected static $connectionName;

    /**
     * @var \Spot\Locator
     */
    protected static $instance;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected static $dblaConnInstance;

    protected abstract function getParamsConnection();

    /**
     * @return \Spot\Locator
     */
    private static function create()
    {
        $cfg = new \Spot\Config();

        $adapter= $cfg->addConnection(static::$connectionName, static::getParamsConnection());


        $adapter->getConfiguration()->setSQLLogger(self::logger());

        return  new \Spot\Locator($cfg);
    }

    /**
     * @return \Spot\Locator
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = self::create();
        }

        return static::$instance;
    }


    /**
     * @param $entity
     * @return \Spot\Mapper
     */
    public static function createMaper($entity)
    {
        return static::getInstance()->mapper($entity);
    }

    /**
     * @param $sql
     * @return \Doctrine\DBAL\Driver\Statement
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function runQuery($sql)
    {
        if (null === static::$dblaConnInstance) {
            static::$dblaConnInstance = DriverManager::getConnection(
                static::getParamsConnection(), new Configuration()
            );
            static::$dblaConnInstance->getConfiguration()->setSQLLogger(self::logger());
        }
        return static::$dblaConnInstance->query($sql);

    }

    private function logger()
    {
        $log = new Logger("slim");
        $formatter = new LineFormatter("[%datetime%] [%level_name%]: %message%\n");
        $rotating  = new RotatingFileHandler(__DIR__ . "/../../logs/slim.log", 0, Logger::DEBUG);
        $rotating->setFormatter($formatter);
        $log->pushHandler($rotating);
        return new MonologSQLLogger($log);
    }
}