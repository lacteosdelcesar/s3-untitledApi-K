<?php namespace App\Entities;


class Vinculacion extends Entity
{
    public  static $table = 'EMPRESAS';
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