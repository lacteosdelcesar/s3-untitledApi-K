<?php namespace App\Entities;

class Area
{
    public  static $table = 'CENTRO_COSTO';
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