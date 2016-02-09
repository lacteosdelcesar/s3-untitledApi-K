<?php namespace App\Entities;

use App\Lib\Orm\OracleEntityManager;

class Empleado extends \App\Entities\Entity
{
    public static $table = 'TERCEROS';
    public static $fields = [
            'CODIGO' => 'cedula',
            'APELLIDO1' => 'apellido1',
            'APELLIDO2' => 'apellido2',
            'NOMBRES' => 'nombres',
            'ESTADO' => 'estado'
        ];

    public static $pk = 'CODIGO';

    protected $data = [
        'cedula'     => '',
        'apellido1'  => '',
        'apellido2'  => '',
        'nombres'    => '',
        'estado'     => ''
    ];

    public static $relations = [
        'contrato' => [
            'entity' => Contrato::class,
            'tipo' => self::UAM,
            'reference' => 'ID_TERC',
            'object' => null
        ]
    ];

    public static function all($fields='')
    {
        $fields = $fields ? $fields : implode(", ", array_keys(static::$fields));
        $sql = 'SELECT '.$fields.' FROM '.$this->data.' WHERE IND_EMPL = 1';
        $result = OracleEntityManager::runQuery($sql);
        $entities = [];
        $data = $result->fetchAll();
        for ($i=0; $i < count($data) ; $i++) {
            $entities[] = new Empleado($data[$i]); 
        }
        return $entities;
    }

    public function getContrato()
    {
        if($this->relations['contrato']['object'] == null) {
            $sql = 'SELECT CODIGO FROM (
                        SELECT CODIGO FROM CONTRATOS WHERE ID_TERC = ' . $this->data['cedula'] . '
                    ORDER BY FECHA_INGRESO DESC)where ROWNUM <= 1';
            $result = OracleEntityManager::runQuery($sql)->fetch();
            static::$relations['contrato']['object'] = new Contrato($result['CODIGO']);
        }
        return static::$relations['contrato']['object'];
    }

    public function vinculacion()
    {
        $sql = 'SELECT E.CODIGO, E.DESCRIPCION FROM CONTRATOS C 
                INNER JOIN EMPRESAS E ON C.ID_EMP = E.CODIGO WHERE ID_TERC = \''.$this->data['cedula'].'\''
                .'ORDER BY C.FECHA_INGRESO DESC';
        $result = OracleEntityManager::runQuery($sql)->fetch();
        return $result;
    }

    public function estadoC()
    {
        $sql = 'SELECT ESTADO FROM (SELECT C.ESTADO FROM CONTRATOS C WHERE ID_TERC = \''.$this->data['cedula'].'\'' 
                .'ORDER BY FECHA_INGRESO DESC)where ROWNUM <= 1';
        $result = OracleEntityManager::runQuery($sql)->fetch();
        return $result['ESTADO'];
    }
}