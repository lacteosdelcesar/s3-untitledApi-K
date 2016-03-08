<?php namespace App\Resources\Users\Actions;

use App\Lib\Responders\JsonResponder;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Entities\User;

class RetriveAction extends Action
{

    public function __construct()
    {
        $this->responder = JsonResponder::class;
    }

    public function execute(array $args)
    {
        if (isset($args['e_id'])) {
        } else {
            $usersMaper = MySQLEntityManager::createMaper(User::class);
            $body = $usersMaper->select('id, nombre, rol_id')->with('rol');
        }
        return $this->responseInfo = ['body' => $body->toArray(), 'status' => self::STATUS_OK];
    }
}