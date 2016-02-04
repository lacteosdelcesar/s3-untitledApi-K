<?php namespace App\Entities;

use Spot\Entity;

class Periodo extends Entity
{
    protected static $table = 'periodos';
    protected static $mapper = PeriodosMaper::class;
    public static function fields()
    {
        return [
            'id'            => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'anio'          => ['type' => 'integer', 'required' => true],
            'numero'        => ['type' => 'integer', 'required' => true],
            'fecha_inicial' => ['type' => 'date'],
            'fecha_final'   => ['type' => 'date']
        ];
    }
}