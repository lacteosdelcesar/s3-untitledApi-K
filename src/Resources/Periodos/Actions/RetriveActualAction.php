<?php namespace App\Resources\Periodos\Actions;

 use App\Entities\Periodo;
 use App\Lib\Actions\Action;
 use App\Lib\Orm\MySQLEntityManager;
 use App\Lib\Responders\JsonResponder;

class RetriveActualAction extends Action
{

    public function __construct()
    {
        $this->responder = JsonResponder::class;
    }

    public function execute(array $args)
    {
        $manager = MySQLEntityManager::createMaper(Periodo::class);
        $periodo = $manager->getActual();
        return $this->responseInfo = ['body' => $periodo, 'status' => self::STATUS_CREATED];
    }
}