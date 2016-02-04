<?php namespace App\Resources\Empleados\Actions;

use App\Entities\Empleado;
use App\Entities\EmpleadosMaper;
use App\Lib\Actions\Action;
use App\Lib\Orm\OracleEntityManager;
use App\Lib\Responders\JsonResponder;

class RetriveAction extends Action
{

    public function __construct()
    {
        $this->responder = JsonResponder::class;
    }

    public function execute(array $args)
    {
        if (isset($args['e_id'])) {
            /**
             * @var Empleado
             */
            $empleado = new Empleado($args['e_id']);
            $body = [
                'cedula' => trim($empleado->cedula),
                'apellido1' => trim($empleado->apellido1),
                'apellido2' => trim($empleado->apellido2),
                'nombres' => trim($empleado->nombres)
            ];
        } else {
            $empleados = EmpleadosMaper::all();
            $body = [];
            foreach ($empleados as $empleado) {
                $body[] = [
                    'cedula' => trim($empleado->cedula),
                    'apellido1' => trim($empleado->apellido1),
                    'apellido2' => trim($empleado->apellido2),
                    'nombres' => trim($empleado->nombres)
                ];
            }
        }
        return $this->responseInfo = ['body' => $body, 'status' => self::STATUS_CREATED];
    }
}