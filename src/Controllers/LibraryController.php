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
			$payload = json_encode(array("Message" => "Se creo exitosamente el usuario ".$data['Nombre']));		
			$response->getBody()->write($payload);
			return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(201);
		}else{
			$payload = json_encode(array("Error" => "No se pudo crear el usuario"));			
			$response->getBody()->write($payload);
			return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(500);
		}
		
	}
}

?>