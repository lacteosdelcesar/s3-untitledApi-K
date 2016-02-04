<?php namespace App\Lib\Responders;


use Slim\Http\Response;

class PdfResponder
{

    private $response;

    public function __construct(Response $response, $responseInfo)
    {
        $this->response = $response
            ->withStatus($responseInfo['status'])
            ->withHeader('Content-Type', 'application/pdf');
    }

    public function __invoke()
    {
        return $this->response;
    }

}