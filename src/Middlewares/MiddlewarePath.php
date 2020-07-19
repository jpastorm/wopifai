<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use App\Models\CancionModel;

class MiddlewarePath
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        
        $hash = $route->getArgument('hash');
        $Cancion = new CancionModel();
        $Cancion->idCancion = $hash;
        $res=$Cancion->findSong();
        $resultado = json_decode($res, true);

        $Ruta = $resultado[0]["Ruta"]."/".$resultado[0]["nombreArchivo"];

        if (!file_exists($Ruta)) {
            echo json_encode(array(
                "Error" => "No se puede encontrar el archivo en la ruta especificada",
                "Ruta" => $Ruta,
                "Status" => 404
            ));
            header("HTTP/1.0 404 Not Found");
            die();
        }
        
        $response = $handler->handle($request);
        //$response->getBody()->write('AFTER');
        $existingContent = (string) $response->getBody();
        $response = new Response();
        $response->getBody()->write($existingContent);
        return $response;           
    }
}

$app->add(new MiddlewarePath());