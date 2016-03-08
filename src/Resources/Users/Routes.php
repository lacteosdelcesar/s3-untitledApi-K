<?php namespace App\Resources\Users;

use Slim\App;
use App\Resources\Users\Actions\RetriveAction;

class Routes
{
    public function __construct(App $app)
    {
        $app->group('/usuarios', function(){
            $this->get('', RetriveAction::class);
        });
    }
}