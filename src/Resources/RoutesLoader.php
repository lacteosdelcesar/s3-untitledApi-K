<?php namespace App\Resources;

use Slim\App;

class RoutesLoader
{
    private $app;
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function bindRoutes()
    {
        new Empleados\Routes($this->app);
        new PQRS\Routes($this->app);
        new Periodos\Routes($this->app);
        new Users\Routes($this->app);
    }
}