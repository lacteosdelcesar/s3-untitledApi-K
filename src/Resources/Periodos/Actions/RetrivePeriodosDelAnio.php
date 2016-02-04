<?php namespace App\Resources\Periodos\Actions;

use App\Entities\Periodo;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Responders\JsonResponder;

class RetrivePeriodosDelAnio extends Action
{

    public function __construct()
    {
        $this->responder = JsonResponder::class;
    }

    public function execute(array $args)
    {   
        $manager = MySQLEntityManager::createMaper(Periodo::class);
        $periodos = $manager->where(['anio' => $args['anio']]);
        if(count($periodos) == 0){
            $periodo = 1;
            for($i=1; $i<=12; $i++){
                $mes = ($i<10) ? "0$i" : $i;
                $periodo1 = $manager->create([
                    'anio' => $args['anio'],
                    'numero' => $periodo,
                    'fecha_inicial' => new \DateTime($args['anio'].'-'.$mes.'-01'),
                    'fecha_final' => new \DateTime($args['anio'].'-'.$mes.'-15')
                ]);
                $periodo++;
                $periodo2 = $manager->create([
                    'anio' => $args['anio'],
                    'numero' => $periodo,
                    'fecha_inicial' => new \DateTime($args['anio'].'-'.$mes.'-16'),
                    'fecha_final' => new \DateTime($args['anio'].'-'.$mes.'-'.date("d",(mktime(0,0,0,$mes+1,1,$args['anio'])-1)))
                ]);          
                $periodo++;
            }
            $periodos = $manager->where(['anio' => $args['anio']]);
        }
        return $this->responseInfo = ['body' => $periodos->toArray(), 'status' => self::STATUS_CREATED];
    }
}