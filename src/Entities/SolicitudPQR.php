<?php namespace App\Entities;

class SolicitudPQR extends \Spot\Entity
{
    protected static $table = 'solicitudes_pqr';
    public static function fields()
    {
        return [
            'id'                    => ['type' => 'string', 'primary' => true, 'autoincrement' => true],
            'cedula_empleado'       => ['type' => 'integer', 'required' => true],
            'mensaje'               => ['type' => 'text', 'required' => true],
            'fecha'                 => ['type' => 'datetime', 'required' => true, 'value' => new \DateTime()],
            'respuesta'             => ['type' => 'text'],
            'fecha_respuesta'       => ['type' => 'datetime'],
            'usuario_que_responde'  => ['type' => 'integer']
        ];
    }
}