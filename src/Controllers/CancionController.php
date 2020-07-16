<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\StreamInterface as Stream;
use Slim\Psr7\Factory\StreamFactory;

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
		$image = imagecreatefrompng(dirname(__FILE__, 3)."/public/assets/default_cover.png");

    	//Color of Text
		$textColor = imagecolorallocate($image, 0, 0, 0);

    	//Full Font-File Path

		$ruta = dirname(__FILE__, 3)."/public/assets/Inter-UI-BlackItalic.ttf";

		$fontPath = $ruta;

    	//Function That Write Text On Image
		imagettftext($image, 15, 0, 5, 280, $textColor, $fontPath, $text);

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

	public function getStreamTrack($request, $response, $args) {
		$Cancion = new CancionModel();
		$Cancion->idCancion = $args['hash'];
		$path = $Cancion->getPath();

		$file = $path[0]->Ruta."/".$path[0]->NombreArchivo;

		$fp = @fopen($file, 'rb');
	    $size   = filesize($file); // File size
	    $length = $size;           // Content length
	    $start  = 0;               // Start byte
	    $end    = $size - 1;       // End byte


		header('Content-type: audio/mpeg');
		header("Accept-Ranges: bytes");
		
		if (isset($_SERVER['HTTP_RANGE'])) {
	        $c_start = $start;
	        $c_end   = $end;
	        list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
	        if (strpos($range, ',') !== false) {
	            header('HTTP/1.1 416 Requested Range Not Satisfiable');
	            header("Content-Range: bytes $start-$end/$size");
	            exit;
	        }
	        if ($range == '-') {
	            $c_start = $size - substr($range, 1);
	        }else{
	            $range  = explode('-', $range);
	            $c_start = $range[0];
	            $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
	        }
	        $c_end = ($c_end > $end) ? $end : $c_end;
	        if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
	            header('HTTP/1.1 416 Requested Range Not Satisfiable');
	            header("Content-Range: bytes $start-$end/$size");
	            exit;
	        }
	        $start  = $c_start;
	        $end    = $c_end;
	        $length = $end - $start + 1;
	        fseek($fp, $start);
	        header('HTTP/1.1 206 Partial Content');
	    }

	    header("Content-Range: bytes $start-$end/$size");
	    header("Content-Length: ".$length);
	    $buffer = 1024 * 8;

	    while(!feof($fp) && ($p = ftell($fp)) <= $end) {
	        if ($p + $buffer > $end) {
	            $buffer = $end - $p + 1;
	        }
	        set_time_limit(0);
	        echo fread($fp, $buffer);
	        flush();
	    }

	    fclose($fp);
	    return $response;

	}
}

?>