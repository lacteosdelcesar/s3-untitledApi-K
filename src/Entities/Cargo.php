<?php namespace App\Entities;

class Cargo extends Entity
{
    public  static $table = 'CARGOS';
    public static $fields = [
        'CODIGO'        => 'codigo',
        'DESCRIPCION'       => 'nombre'
    ];
    public static $pk = 'CODIGO';
    protected $data = [
        'codigo'             => '',
        'nombre'    => ''
    ];
}