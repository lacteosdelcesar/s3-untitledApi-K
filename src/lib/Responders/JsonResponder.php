<?php namespace App\Lib\Responders;


use Slim\Http\Response;

class JsonResponder
{
    private $response;

    public function __construct(Response $response, $responseInfo)
    {
        $body = json_encode($responseInfo['body']);
        $this->response = $response
            ->withStatus($responseInfo['status'])
            ->withHeader('Content-type', 'application/json')
            ->write($body);
    }

    public function __invoke()
    {
        return $this->response;
    }

}