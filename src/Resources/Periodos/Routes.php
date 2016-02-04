<?php namespace App\Resources\Periodos;

use App\Resources\Periodos\Actions\RetriveActualAction;
use App\Resources\Periodos\Actions\RetrivePeriodosDelAnio;
use Slim\App;

class Routes
{
    public function __construct(App $app)
    {
        $app->group('/periodos', function(){
            $this->get('/actual', RetriveActualAction::class);
            $this->get('/{anio}', RetrivePeriodosDelAnio::class);
        });
    }
}