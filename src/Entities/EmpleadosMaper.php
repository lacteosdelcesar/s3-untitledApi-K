<?php namespace App\Entities;

use App\Lib\Orm\OracleEntityManager;

class EmpleadosMaper
{
    public static function all($fields='')
    {
        $fields = $fields ? $fields : implode(", ", array_keys(Empleado::$fields));
        $sql = 'SELECT '.$fields.' FROM '.Empleado::$table." WHERE IND_EMPL = 1 AND CODIGO <> ' '";
        $result = OracleEntityManager::runQuery($sql);
        $entities = [];
        $data = $result->fetchAll();
        for ($i=0; $i < count($data) ; $i++) {
        	$empleado = new Empleado($data[$i]);
            $entities[] = $empleado; 
        }
            //array_push($entities, new Empleado($data));
        return $entities;
    }
}