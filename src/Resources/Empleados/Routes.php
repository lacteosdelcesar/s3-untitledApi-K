<?php namespace App\Resources\Empleados;


use App\Resources\Empleados\Actions\GenerateCertificadoAction;
use App\Resources\Empleados\Actions\RetriveAction;
use App\Resources\Empleados\Actions\SyncUP;
use Slim\App;

class Routes
{
    public function __construct(App $app)
    {
        $app->group('/empleados', function(){
            $this->get('/syncup', SyncUP::class);
            $this->get('[/]', RetriveAction::class);
            $this->get('/{e_id}', RetriveAction::class);
            //$this->get('/certificado_laboral/{crt_id}', GenerateCertificadoAction::class);
            $this->get('/{e_id}/certificado_laboral/{tipo_crt}', GenerateCertificadoAction::class);
            #$this->get('/{e_id}', RetriveAction::class);
        });
    }
}