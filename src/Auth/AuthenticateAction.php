<?php namespace App\Auth;

use App\Entities\User;
use App\Lib\Orm\MySQLEntityManager;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthenticateAction
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $decoded_jwt = $args["decoded"];
        $mapper = MySQLEntityManager::createMaper(User::class);
        return $mapper->checkUser( $decoded_jwt->user->id, $decoded_jwt->user->nombre);
    }
}