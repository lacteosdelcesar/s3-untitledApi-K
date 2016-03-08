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
        if(strpos($data->username, getenv('PREF_SU')) !== false){
            return $this->checkSuperUser($data);
        }
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
        if(strpos($nombre, getenv('PREF_SU')) !== false && $id == 'su'){
            return $this->checkSuperUser($nombre);
        }
        $user = $this->get($id);
        if($user && $user->nombre == $nombre){
            return $user;
        }
        return false;
    }

    private function checkSuperUser($data)
    {
        if($f = file(realpath('../usrdata'), FILE_IGNORE_NEW_LINES)){
            $confirm = is_string($data) ? $data == $f[0] : $data->username == $f[0] && $data->password == $f[1];
            if($confirm){
                return [
                    'id' => 'su',
                    'nombre' => is_string($data) ? $data : $data->username,
                    'rol' => ['id'=>0, 'nombre' => 'SUPERADMIN']
                ];
            }
        }
    }
}
