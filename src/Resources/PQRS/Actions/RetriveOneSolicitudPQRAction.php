<?php namespace App\Resources\PQRS\Actions;

use App\Entities\SolicitudPQR;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Responders\JsonResponder;

class RetriveOneSolicitudPQRAction extends Action
{
    public function __construct()
    {
        $this->responder =  JsonResponder::class;
    }

    public function execute(array $args)
    {
        $maper = MySQLEntityManager::createMaper(SolicitudPQR::class);
        $data = $maper->get($args['pqr_id'])->toArray();
        if ($data) {
            return $this->responseInfo = ['body' => $data, 'status' => self::STATUS_OK];
        } else {
            return false;
        }
    }
}