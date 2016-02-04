<?php namespace App\Entities;

use App\Lib\Orm\OracleEntityManager;

abstract class Entity
{
    const MAU = 'MAU';
    const UAM = 'UAM';

    protected $entitimanager;

    public static $table = '';
    public static $fields = [];
    public static $pk = '';
    protected $data = [];
    public static $relations = [];

    /**
     * @param string|array null $param
     */
    public function __construct($param = null)
    {
        if(!is_array($param)){
            $this->find($param);
        } else {
            $this->setData($param);
        }
    }

    public function find($key, $fields='')
    {
        $fields = $fields ? $fields : implode(", ", array_keys(static::$fields));
        $sql = 'SELECT '.$fields.' FROM '.static::$table.' WHERE '.static::$pk.' = \''.$key.'\'';
        $result = OracleEntityManager::runQuery($sql);
        if($data = $result->fetch()){
            $this->setData($data);
        }
    }

    public function getRelacion($relation)
    {
        if(static::$relations[$relation]['object'] == null) {
            $entity = static::$relations[$relation]['entity'];
            if(self::MAU == static::$relations[$relation]['tipo']){
                $fk = $this->data[static::$fields[static::$relations[$relation]['fk']]];
                static::$relations[$relation]['object'] = new $entity($fk);
            }elseif(self::UAM == static::$relations[$relation]['tipo']){
                $sql = 'SELECT * FROM ('.
                        'SELECT '.$entity::$pk.' '.
                        'FROM '.$entity::$table.' '.
                        'WHERE '.static::$relations[$relation]['reference'].' = '.
                        $this->data[static::$fields[static::$pk]] . '
                    )where ROWNUM <= 1';
                $result = OracleEntityManager::runQuery($sql)->fetch();
                static::$relations[$relation]['object'] = new $entity($result['CODIGO']);
            }
        }
        return static::$relations[$relation]['object'];
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        foreach ($data as $clave => $valor) {
            $this->data[static::$fields[$clave]] = trim($valor);
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function __get($field)
    {
        if (array_key_exists($field, $this->data)) {
            return $this->data[$field];
        }
    }

    public function __call($name, $arguments)
    {
        if(method_exists(static::class, $name)){
            return static::$name;
        }else{
            $relation = strtolower(substr($name, 3));
            if(array_key_exists($relation, static::$relations)){
                return $this->getRelacion($relation);
            }else{
                throw new \Exception('metodo '.$name.' no enxiste en la clase '.static::class);
            }
        }
    }
}