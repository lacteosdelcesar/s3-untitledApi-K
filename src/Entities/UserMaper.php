<?php namespace App\Entities;

use Spot\Mapper;

class UserMaper extends Mapper
{
    public function checkUser()
    {
        return call_user_func_array(array($this, 'checkUser'.func_num_args()), func_get_args());
    }

    public function checkUser1($data)
    {
        $user = $this->select('id, nombre, rol_id, distrito_id, area_id, contrasena')
            ->where(['nombre' => $data->username])
            ->with('rol')->first();
        if($user){
            if (password_verify($data->password, $user->contrasena)) {
                $arr = $user->toArray();
                unset($arr['contrasena']);
                unset($arr['create_time']);
                unset($arr['rol_id']);
                $arr['rol'] = $user->rol->toArray();
                $user = $arr;
            } else {
                return false;
            }
            return $user;
        }
        return false;
    }

    public function checkUser2($id, $nombre)
    {
        $user = $this->get($id);
        if($user && $user->nombre == $nombre){
            return $user;
        }
        return false;
    }
}