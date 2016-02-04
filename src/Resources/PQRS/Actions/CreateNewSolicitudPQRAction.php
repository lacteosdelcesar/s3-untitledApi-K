<?php namespace App\Resources\PQRS\Actions;

use App\Entities\SolicitudPQR;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use \App\Lib\Responders\JsonResponder;

class CreateNewSolicitudPQRAction extends Action
{
    public function __construct()
    {
        $this->responder =  JsonResponder::class;
    }

    public function execute(array $args)
    {
        $data = json_decode($this->request->getBody());
        $maper = MySQLEntityManager::createMaper(SolicitudPQR::class);
        $result = $maper->create([
            'cedula_empleado' => $data->cedula_empleado,
            'mensaje' => $data->mensaje
        ]);
        if ($result) {
            return $this->responseInfo = ['body' => $result, 'status' => self::STATUS_CREATED];
        } else {
            return false;
        }
    }
}