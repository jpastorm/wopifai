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
	public static function GenerateCover($nombre){

			 //Text To Add
		$text = $nombre;

    //Background Image - The Image To Write Text On
		$image = imagecreatefrompng("https://cdn4.iconfinder.com/data/icons/media-player-icons/80/Media_player_icons-09-512.png");

    //Color of Text
		$textColor = imagecolorallocate($image, 229, 85, 78);

    //Full Font-File Path

		$ruta=dirname(__FILE__, 3)."/public/assets/COMICATE.TTF";

		$fontPath = $ruta;

    //Function That Write Text On Image
		imagettftext($image, 60, 15, 5, 320, $textColor, $fontPath, $text);

    //Set Browser Content Type
		//header('Content-type: image/png');

    //Send Image To Browser
		//imagepng($image);

		ob_start();
		imagepng($image);
		$data = ob_get_contents();
		ob_end_clean();

		return $data;
		
	}
	public function getAlbumCover($request, $response, $args)
	{


		header('Content-Type:image/png');
		$Cancion = new CancionModel();
		$Cancion->idCancion = $args['hash'];
		$path = $Cancion->getPath();	
		if ($path == false) {
			$response->getBody()->write(json_encode(array("error" => "No se encontro la ruta especificada" )));
			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(404);
		}
		$coverData = Explorador::GetAlbumCover($path[0]->Ruta."/".$path[0]->NombreArchivo);
		if(is_null($coverData['cover'])) {

			$data = self::GenerateCover($path[0]->NombreArchivo);
			$response->getBody()->write($data);
			$response = $response->withHeader('Content-type', 'image/png');     
			return $response;			

		}else{

			$response->getBody()->write($coverData['cover']);
			return $response
			->withHeader('Content-type', $coverData['mimetype'])
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