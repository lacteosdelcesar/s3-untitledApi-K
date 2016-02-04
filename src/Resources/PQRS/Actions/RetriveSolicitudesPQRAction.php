<?php namespace App\Resources\PQRS\Actions;

use App\Entities\Empleado;
use App\Entities\SolicitudPQR;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Responders\JsonResponder;

class RetriveSolicitudesPQRAction extends Action
{

    public function __construct()
    {
        $this->responder =  JsonResponder::class;
    }

    public function execute(array $args)
    {
        $maper = MySQLEntityManager::createMaper(SolicitudPQR::class);
        $solicitudes_pqr = $maper->query(
            'SELECT s1.* FROM solicitudes_pqr s1 ORDER BY s1.fecha'
        );
        $data = [];
        foreach ($solicitudes_pqr as $solicitud) {
            $s = $solicitud->toArray();
            $empleado = new Empleado($solicitud->cedula_empleado);
            $s['empleado'] =  $empleado->getData();
            array_push($data, $s);
        }
        if ($data) {
            return $this->responseInfo = ['body' => $data, 'status' => self::STATUS_OK];
        } else {
            return false;
        }
    }
}