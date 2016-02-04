<?php namespace App\Lib\Responders;

use Slim\Http\Response;

class DownloadResponder
{
    private $response;

    public function __construct(Response $response, $responseInfo)
    {
        $body = json_encode($responseInfo['body']);
        $this->response = $response
            ->withStatus($responseInfo['status'])
            ->withHeader('Content-Type', 'application/octet-stream')
            ->write($body);
    }

    public function __invoke()
    {
        return $this->response;
    }

}