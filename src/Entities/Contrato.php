<?php namespace App\Entities;

use App\Lib\Orm\OracleEntityManager;

class Contrato extends Entity
{
    public static $table = 'CONTRATOS';
    public static $fields = [
        'CODIGO'        => 'codigo',
        'ID_TERC'       => 'cedula_empleado',
        'ESTADO'        => 'estado',
        'ANO_RETIRO'    => 'ano_retiro',
        'LAPSO_RETIRO'  => 'lapso_retiro',
        'FECHA_RETIRO'  => 'fecha_retiro',
        'ANO_INGRESO'   => 'ano_ingreso',
        'LAPSO_INGRESO' => 'lapso_ingreso',
        'FECHA_INGRESO' => 'fecha_ingreso',
        'FECHA_CONT_HA' => 'fecha_finalizacion',
        'SALARIO'       => 'salario',
        'ID_CARGO'      => 'cargo_id',
        'ID_EMP'        => 'vinculacion_id',
        'ID_CO'         => 'distrito_id',
        'ID_CCOSTO'     => 'area_id'
    ];
    public static $pk = 'CODIGO';
    protected $data = [
        'codigo'             => '',
        'cedula_empleado'    => '',
        'estado'             => '',
        'ano_retiro'         => '',
        'lapso_retiro'       => '',
        'fecha_retiro'       => '',
        'ano_ingreso'        => '',
        'lapso_ingreso'      => '',
        'fecha_ingreso'      => '',
        'fecha_finalizacion' => '',
        'salario'            => '',
        'cargo_id'           => '',
        'vinculacion_id'     => '',
        'distrito_id'        => '',
        'area_id'            => ''
    ];

    public static $relations = [
        'vinculacion' => [
            'entity' => Vinculacion::class,
            'tipo' => self::MAU,
            'fk' => 'ID_EMP',
            'object' => null
        ],
        'distrio' => [
            'entity' => Distrito::class,
            'tipo' => self::MAU,
            'fk' => 'ID_CO',
            'object' => null
        ],
        'area' => [
            'entity' => Area::class,
            'tipo' => self::MAU,
            'fk' => 'ID_CCOSTO',
            'object' => null
        ],
        'cargo' => [
            'entity' => Cargo::class,
            'tipo' => self::MAU,
            'fk' => 'ID_CARGO',
            'object' => null
        ],
    ];

    public function getEstado()
    {
        return ($this->data['estado'] == 'R') ? 'retirado' : 'ativo';
    }

    public function getTipo()
    {
        return ($this->data['fecha_finalizacion'] == '99999999') ?
            'a término indefinido' : 'a término fijo';
    }

    public function salarioPromedio()
    {
        $sql = 'select * from( 
                SELECT LAPSO_DOC, SUM(NMMOV_VALOR) as valor FROM NMRESUMEN_PAGOS_NOMINA 
                WHERE ID_TERC='.$this->data['cedula_empleado'].' AND ID_IND_DEV_DED = 1 AND ID_TIPO_DOC = \'NQ\' 
                AND  ID_CPTO != 673 GROUP BY LAPSO_DOC ORDER BY LAPSO_DOC DESC 
                )where rownum <= 3';
        $result = OracleEntityManager::runQuery($sql)->fetchAll();
        $sum = 0;
        for($i=0; $i<count($result); $i++){
            $sum += $result[$i]['VALOR'];
        }
        return $i>0 ? $sum/$i : 0;
    }
}