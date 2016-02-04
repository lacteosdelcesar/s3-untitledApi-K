<?php namespace App\Resources\PQRS;

use App\Resources\PQRS\Actions\CreateNewSolicitudPQRAction;
use App\Resources\PQRS\Actions\DeleteSolicitudPQRAction;
use App\Resources\PQRS\Actions\ResponderSolicitudPQRAction;
use App\Resources\PQRS\Actions\RetriveLastSolicitudPQRAction;
use App\Resources\PQRS\Actions\RetriveOneSolicitudPQRAction;
use App\Resources\PQRS\Actions\RetriveSolicitudesPQRAction;
use Slim\App;

class Routes
{

    public function __construct(App $app)
    {
        $app->group('/empleados/{e_id}/solicitudes_pqr', function(){
            $this->get('', RetriveLastSolicitudPQRAction::class);
            $this->post('', CreateNewSolicitudPQRAction::class);
            $this->delete('/{pqr_id}', DeleteSolicitudPQRAction::class);
            $this->post('/{pqr_id}/respuesta', ResponderSolicitudPQRAction::class);
        });
        $app->get('/solicitudes_pqr', RetriveSolicitudesPQRAction::class);
        $app->get('/solicitudes_pqr/{pqr_id}', RetriveOneSolicitudPQRAction::class);
    }
}