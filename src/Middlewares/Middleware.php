<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class MiddlewareCors
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        /*
        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();
    
        $response = new Response();
        $response->getBody()->write('BEFORE' . $existingContent);
    
        return $response;
        */
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        //header('Content-type: application/json; charset=utf-8');

        
        $_POST = json_decode(file_get_contents("php://input"), true);

        $response = $handler->handle($request);
        //$response->getBody()->write('AFTER');
        $existingContent = (string) $response->getBody();
        $response = new Response();
        $response->getBody()->write($existingContent);
        return $response;           
    }
}

$app->add(new MiddlewareCors());