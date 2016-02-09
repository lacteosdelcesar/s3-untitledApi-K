<?php
date_default_timezone_set('America/Bogota');
setlocale(LC_ALL, 'spanish');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Content-Type: application/json; charset=iso-8859-1');

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
#$dotenv->required('MYSQL_DATABASE_DSN');
$dotenv->load();

include('Auth/config.php');

$app->get('/estas', function(){
	echo 'aqui estoy!';
});

$routesLoader = new App\Resources\RoutesLoader($app);
$routesLoader->bindRoutes();

