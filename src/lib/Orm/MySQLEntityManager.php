<?php namespace App\Lib\Orm;

class MySQLEntityManager extends \App\Lib\Orm\EntityManager
{
    protected static $instance;
    protected static $connectionName = 'mysql';

    protected function getParamsConnection()
    {
        return array(
            'driver'   => 'pdo_mysql',
            'host' => getenv('MYSQL_HOST'),
            'dbname' => getenv('MYSQL_DBNAME'),
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASS')
        );
    }

}