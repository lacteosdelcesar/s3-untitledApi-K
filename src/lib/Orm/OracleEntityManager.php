<?php namespace App\Lib\Orm;

class OracleEntityManager extends \App\Lib\Orm\EntityManager
{
    protected static $instance;
    protected static $connectionName = 'oracle';

    protected function getParamsConnection()
    {
        return array(
            'driver'   => 'oci8',
            'host' => getenv('ORACLE_HOST'),
            //'port' => getenv('ORACLE_PORT'),
            'dbname' => getenv('ORACLE_SID'),
            'user' => getenv('ORACLE_USER'),
            'password' => getenv('ORACLE_PASS')
        );
    }
}