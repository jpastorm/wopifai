<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\CancionModel;
use App\Models\MetaDatoModel;
use App\Libraries\Explorador;
/**
 * 
 */
class CancionController
{
	
	public function Gethash($idCancion) 
	{	
		$Cancion = new CancionModel();	
		$Cancion->idCancion = $idCancion;
		$check = $Cancion->checkHash();
		return $check;
	}
	public function AddCancion($idLibreria,$idCancion,$NombreArchivo) 
	{   	
		$check = self::Gethash($idCancion);

		if ($check == false) {
			$Cancion = new CancionModel();
			$Cancion->idLibreria = $idLibreria;
			$Cancion->idCancion = $idCancion;
			$Cancion->NombreArchivo = $NombreArchivo;

			$res=$Cancion->createCancion();
			if ($res) {
				return true;
			}else{
				return false;
			}
		}
		
	}
	public function getAlbumCover($request, $response, $args)
	{
		$Cancion = new CancionModel();
		$Cancion->idCancion = $args['hash'];
		$path = $Cancion->getPath();	
		if ($path == false) {
			$response->getBody()->write(json_encode(array("error" => "No se encontro la ruta especificada" )));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}
		$coverData = Explorador::GetAlbumCover($path[0]->Ruta."/".$path[0]->NombreArchivo);
		if(is_null($coverData['cover'])) {
			$response->getBody()->write(json_encode(array("error" => "No se encontro el cover de la cancion" )));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}else{

			$response->getBody()->write($coverData['cover']);
			return $response
			->withHeader('content-type', $coverData['mimetype'])
			->withStatus(200);
			//return $response->withHeader('Content-Type', 'application/force-download');
	
		}

	}
	public function getMeta($request,$response,$args){
		$MetaDato = new MetaDatoModel();
		$MetaDato->idCancion = $args['hash'];
		$res = $MetaDato->getMeta();				
		$response->getBody()->write(json_encode($res));
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);		

	}
	public function getStreamTrack($request,$response,$args)
	{	
		$Cancion = new CancionModel();
		$Cancion->idCancion = $args['hash'];
		$path = $Cancion->getPath();

		$file = $path[0]->Ruta."/".$path[0]->NombreArchivo;
		$contenido = file_get_contents($file);
		$response->getBody()->write($contenido);
		return $response->withHeader('Content-Type', 'application/force-download');
		
	}
	public function getSong($request,$response,$args)
	{	
		$Cancion = new CancionModel();
		$result = $Cancion->buscador($args["nombre"]);
		if ($result == false) {

			$response->getBody()->write(json_encode(array("Message" => "No content" )));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}

		$response->getBody()->write(json_encode($result));
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);
	}
	public function getCancion($request,$response,$args)
	{
		$Cancion = new CancionModel();
		$result = $Cancion->getInformation($args["hash"]);
		if ($result == false) {

			$response->getBody()->write(json_encode(array("Message" => "No content" )));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}

		$response->getBody()->write(json_encode($result));
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);
	}
}

?>