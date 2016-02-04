<?php namespace App\Entities;

use Spot\Entity;

class Rol extends Entity
{
    protected static $table = 'roles';
    public static function fields()
    {
        return [
            'id'      => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'nombre'  => ['type' => 'string', 'required' => true, 'unique' => true]
        ];
    }
}