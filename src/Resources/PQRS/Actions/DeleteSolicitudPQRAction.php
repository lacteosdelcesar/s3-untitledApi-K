<?php namespace App\Resources\PQRS\Actions;

use App\Entities\SolicitudPQR;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Responders\JsonResponder;

class DeleteSolicitudPQRAction extends Action
{

    public function __construct()
    {
        $this->responder =  JsonResponder::class;
    }

    public function execute(array $args)
    {
        $maper = MySQLEntityManager::createMaper(SolicitudPQR::class);
        if ($maper->delete(['id' => $args['pqr_id']])) {
            return $this->responseInfo = ['body' => '', 'status' => self::STATUS_OK];
        } else {
            return false;
        }
    }
}