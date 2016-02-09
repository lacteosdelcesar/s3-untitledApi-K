<?php namespace App\Auth;

use App\Entities\User;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Responders\JsonResponder;

class ChangePasswordAction extends Action
{
    public function __construct()
    {
        $this->responder = JsonResponder::class;
    }

    public function execute(array $args)
    {
        $data = json_decode($this->request->getBody());
        $mapper = MySQLEntityManager::createMaper(User::class);
        $user = $mapper->checkUser($data->id_user, $data->username);
        if ($user) {
            $user = $mapper->get($data->id_user);
            $user->contrasena =  password_hash($data->new_password, PASSWORD_BCRYPT);
            $mapper->update($user);
            return $this->responseInfo = ['body' => ['body' => 'contraseña actualizada'],'status' => self::STATUS_OK];
        } else {
            return false;
        }
    }
}