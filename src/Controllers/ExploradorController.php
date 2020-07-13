<?php 
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Libraries\Explorador;
use App\Models\LibraryModel;
use App\Models\CancionModel;
use App\Models\MetaDatoModel;
/**
 * 
 */
class ExploradorController
{

	public function getDir($request, $response, $args){

		$libraries = Explorador::ListarDirectorio('/home/pordefecto/Música/Rap');
		//$response->getBody()->write(json_encode($libraries));
		//$lastSegment = basename(parse_url($url, PHP_URL_PATH));
		//var_dump(basename(dirname($libraries[0]["totalPath"])));
		for ($i=0; $i <count($libraries) ; $i++) { 
			$Library = new LibraryModel();
			$Library->Nombre = basename(dirname($libraries[$i]["totalPath"]));
			$Library->Ruta = $libraries[$i]["totalPath"];

			$res = $Library->createLibrary();

			if ($res == false) {

				$data = array(
					'message' => 'Error en la creacion de la libreria',
					'status' => 500,
					'data' => $libraries);

				$response->getBody()->write(json_encode($data));

				return $response
				->withHeader('content-type', 'application/json')
				->withStatus(500);
			}

			$idLibreria = $res;
			$Cancion = new CancionModel();
			$Cancion->idLibreria = $idLibreria;
			$Cancion->idCancion = $libraries[$i]["hash"];
			$Cancion->NombreArchivo = $libraries[$i]["filename"];

			$res=$Cancion->createCancion();

			if ($res == false || is_null($libraries[$i]["hash"])) {

				$data = array(
					'message' => 'Error en la creacion de la Cancion',
					'status' => 500,
					'data' => $libraries);

				$response->getBody()->write(json_encode($data));

				return $response
				->withHeader('content-type', 'application/json')
				->withStatus(500);
			}


			$idCancion = $libraries[$i]["hash"];
			$MetaDato = new MetaDatoModel();
			$MetaDato->idCancion = $idCancion;
			$MetaDato->Artista = $libraries[$i]['metaData']['Artista'];
			$MetaDato->Titulo = $libraries[$i]['metaData']['Titulo'];
			$MetaDato->Album = $libraries[$i]['metaData']['Album'];
			$MetaDato->Track = $libraries[$i]['metaData']['Track'];
			$MetaDato->Genero = $libraries[$i]['metaData']['Genero'];
			$MetaDato->Anio = $libraries[$i]['metaData']['Anio'];
			
			$res=$MetaDato->createMetaDato();

			if ($res == false) {

				$data = array(
					'message' => 'Error en la creacion de la MetaData',
					'status' => 500,
					'data' => $libraries);

				$response->getBody()->write(json_encode($data));

				return $response
				->withHeader('content-type', 'application/json')
				->withStatus(500);
			}

			$data = array(
				'message' => 'Biblioteca Creada',
				'status' => 201,
				'data' => $libraries);

		}
		$response->getBody()->write(json_encode($data));
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);
	}

	public function getTags($request, $response, $args){

		$tag = Explorador::GetTags('/home/pordefecto/Música/algotres.mp3');
		$response->getBody()->write(json_encode($tag));
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);
	}
	public function getAlbumCover($request, $response, $args){

		$coverData = Explorador::GetAlbumCover('/home/pordefecto/Música/algotres.mp3');
		if(is_null($coverData['cover'])) {
			$response->getBody()->write(json_encode(array("error" => "NULO PE :V" )));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}else{

			$response->getBody()->write($coverData['cover']);
			return $response
			->withHeader('content-type', $coverData['mimetype'])
			->withStatus(200);	
		}

	}
	public function StreamFile($request, $response,$args)
	{	
		$file = "/home/pordefecto/Música/algotres.mp3";
		$contenido = file_get_contents($file);
		$response->getBody()->write($contenido);
		return $response->withHeader('Content-Type', 'application/force-download');
	}

}

?>