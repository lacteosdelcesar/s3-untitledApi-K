<?php namespace App\Resources\PQRS\Actions;


use App\Entities\SolicitudPQR;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Responders\JsonResponder;

class ResponderSolicitudPQRAction extends Action
{

    public function __construct()
    {
        $this->responder =  JsonResponder::class;
    }

    public function execute(array $args)
    {
        $data = json_decode($this->request->getBody());
        $maper = MySQLEntityManager::createMaper(SolicitudPQR::class);
        $solicitud_pqr = $maper->get($data->id);
        if ($solicitud_pqr) {
            $solicitud_pqr->respuesta = $data->respuesta;
            $solicitud_pqr->fecha_respuesta = new \DateTime();
            $solicitud_pqr->usuario_que_responde = $data->user_id;
            $maper->update($solicitud_pqr);
            return $this->responseInfo = ['body' => '', 'status' => self::STATUS_OK];
        } else {
            return false;
        }
    }
}