<?php namespace App\Resources\Empleados\Actions;

use App\Entities\Rol;
use App\Entities\User;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Orm\OracleEntityManager;
use App\Lib\Responders\JsonResponder;
use App\Entities\EmpleadosMaper;

class SyncUP extends Action
{

    public function __construct()
    {
        $this->responder = JsonResponder::class;
    }

    public function execute(array $args)
    {
        $usersMaper = MySQLEntityManager::createMaper(User::class);
        $rolesMaper = MySQLEntityManager::createMaper(Rol::class);
        $count_nuevos = 0;
        $count_actualizados = 0;

        $empleados = EmpleadosMaper::all();
        $rolEmpleados = $rolesMaper->first(['nombre' => 'EMPL_ORD']);
        $rolEmpleadosTemporales = $rolesMaper->first(['nombre' => 'EMPL_ORD_TEMP']);
        $rolEmpleadosInactivos = $rolesMaper->first(['nombre' => 'EMPL_ORD_INACTIVO']);
        foreach($empleados as $empleado){
            $usuario = null;
            if(!$usuario = $usersMaper->first(['nombre'=>$empleado->cedula])){
                
                $vinculacion_empleado = $empleado->vinculacion();
                if($vinculacion_empleado['CODIGO'] == '01'){
                    if($empleado->estadoC() == 'R') {
                        $usuario = $usersMaper->build([
                            'nombre' => $empleado->cedula,
                            'contrasena' => password_hash($empleado->cedula, PASSWORD_BCRYPT),
                            'rol_id' => $rolEmpleadosInactivos->id
                        ]);
                    } else {
                        $usuario = $usersMaper->build([
                            'nombre' => $empleado->cedula,
                            'contrasena' => password_hash($empleado->cedula, PASSWORD_BCRYPT),
                            'rol_id' => $rolEmpleados->id
                        ]);
                    }                    
                }else{ 
                    $usuario = $usersMaper->build([
                        'nombre' => $empleado->cedula,
                        'contrasena' => password_hash($empleado->cedula, PASSWORD_BCRYPT),
                        'rol_id' => $rolEmpleadosTemporales->id
                    ]);
                }
                $usersMaper->save($usuario);
                ++$count_nuevos;
            } else {
                $vinculacion_empleado = $empleado->vinculacion();
                if($vinculacion_empleado['CODIGO'] == '01'){
                    if($empleado->estadoC() == 'R') {
                        $usuario->rol_id = $rolEmpleadosInactivos->id;
                    } else {
                        $usuario->rol_id = $rolEmpleados->id;
                    }                    
                }else{ 
                    $usuario->rol_id = $rolEmpleadosTemporales->id;
                }
                $usersMaper->save($usuario);
                ++$count_actualizados;
            }
        }
        $body = [
            'empleados agregados' => $count_nuevos,
            'empleados actualizados' => $count_actualizados,
            'total_empleados' => count($empleados)
        ];
        return $this->responseInfo = ['body' => $body, 'status' => self::STATUS_CREATED];
    }
}
