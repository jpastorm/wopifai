<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\LibraryModel;
/**
 * 
 */
class LibraryController
{
	
	public function GetLibrary(Request $request, Response $response) 
	{
		$Library = new LibraryModel();

		$data = $Library->listLibrary();

		if ($data) {
			$payload = json_encode($data);			
			$response->getBody()->write($payload);
			return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
		}else{
			$payload = json_encode(array("Message" => "No se encontraron librerias"));			
			$response->getBody()->write($payload);
			return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(204);
		}
		
	}
	public function AddLibrary(Request $request, Response $response) 
	{   
		$data = $request->getParsedBody();

		$Library = new LibraryModel();

		$Library->Nombre = $data['Nombre'];

		$Library->Ruta = $data['Ruta'];

		$res = $Library->createLibrary();

		if ($res) {
			$payload = json_encode(array("Message" => "Se creo exitosamente la libreria ".$data['Nombre']));		
			$response->getBody()->write($payload);
			return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(201);
		}else{
			$payload = json_encode(array("Error" => "No se pudo crear la libreria"));			
			$response->getBody()->write($payload);
			return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(500);
		}
		
	}

	public function GetContentLibrary($request,$response,$args)
	{	
		$Library = new LibraryModel();		
		if (@is_null($args['id'])) {
			$res = $Library->listContent();
		}else{
			$Library->idLibreria = $args['id'];
			$res = $Library->listContentId();	
		}
		if ($res == false) {
			$response->getBody()->write(json_encode(array("Message" => "No se encontraron resultados")));
			return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(404);
		}
		$response->getBody()->write(json_encode($res));
		return $response
		->withHeader('Content-Type', 'application/json')
		->withStatus(200);

	}
}

?>