<?php namespace App\Entities;

class Distrito extends Entity
{
    public  static $table = 'CENTRO_OPERACIONES';
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