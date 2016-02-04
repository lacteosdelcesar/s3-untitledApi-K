<?php namespace App\Entities;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;
use App\Entities\UserMaper as MyMaper;

class User extends \Spot\Entity
{
    protected static $table = 'users';
    protected static $mapper = MyMaper::class;
    public static function fields()
    {
        return [
            'id'              => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'nombre'          => ['type' => 'string', 'required' => true, 'unique' => true],
            'contrasena'      => ['type' => 'string', 'required' => true],
            'rol_id'          => ['type' => 'integer', 'required' => true],
            'distrito_id'     => ['type' => 'string'],
            'area_id'         => ['type' => 'string'],
            'create_time'     => ['type' => 'datetime', 'value' => new \DateTime()]
        ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'rol' => $mapper->belongsTo($entity, Rol::class, 'rol_id')
        ];
    }
}