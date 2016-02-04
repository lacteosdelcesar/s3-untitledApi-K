<?php namespace App\Entities;


use Spot\Entity;

class Certificado extends Entity
{
    protected static $table = 'certificados_laborales';
    public static function fields()
    {
        return [
            'id'           => ['type' => 'string', 'primary' => true],
            'cedula_empleado'  => ['type' => 'integer', 'required' => true],
            'fecha'        => ['type' => 'datetime', 'value' => new \DateTime()],
            'tipo'         => ['type' => 'integer', 'required' => true]
        ];
    }
}