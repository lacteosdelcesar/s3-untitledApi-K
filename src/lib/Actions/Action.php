<?php namespace App\Lib\Actions;


use Slim\Http\Request;
use Slim\Http\Response;

abstract class Action
{
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;
    const STATUS_ACCEPTED = 202;

    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;
    const STATUS_NOT_ACCEPTED = 406;

    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;

    protected $domain;
    protected $responder;

    protected $responseInfo;
    protected $request;

    public abstract function __construct();
    public abstract function execute(array $args);

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->request = &$request;
        if ($this->execute($args)) {
            $responder = new $this->responder($response, $this->responseInfo);
            return $responder();
        } else {
            return $response->withStatus(self::STATUS_NOT_FOUND);
        }
    }
}