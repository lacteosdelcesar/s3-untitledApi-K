<?php
/**
 * Created by tav0
 * Date: 13/01/16
 * Time: 09:21 AM
 */

$authConfig = [
    "secure" => false, //para que acepte http
    "secret" => getenv("SECRET_KEY"),
    "rules" => [
        new \Slim\Middleware\JwtAuthentication\RequestPathRule([
            "path" => "/",
            "passthrough" => ["/token"]
        ]),
        new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
            "passthrough" => ["OPTIONS"]
        ])
    ],
    "callback" => function($request,$response,$args) use ($app) {
        $action = new App\Auth\AuthenticateAction();
        return $action($request,$response,$args);
    }
];


$app->add(new \Slim\Middleware\JwtAuthentication($authConfig));

$app->post("/token", App\Auth\AuthorizeAction::class);
$app->post("/change_password", App\Auth\ChangePasswordAction::class);