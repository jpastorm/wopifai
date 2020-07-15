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
		$response->getBody()->write(json_encode($libraries));		
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);
	}

	public function getTags($request, $response, $args){

		$tag = Explorador::GetTags('/home/pordefecto/Música/Rock/algotres.mp3');
		$response->getBody()->write(json_encode($tag));
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);
	}
	public function getAlbumCover($request, $response, $args){

		$coverData = Explorador::GetAlbumCover('/home/pordefecto/Música/Rap/algodos.mp3');
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
		$file = "/home/pordefecto/Música/Rock/algotres.mp3";
		$contenido = file_get_contents($file);
		$response->getBody()->write($contenido);
		return $response->withHeader('Content-Type', 'application/force-download');
	}
	public function ScanFile($request, $response ,$args)
	{
		
		$lista = array();
		$Library = new LibraryModel();
		$Rutas = $Library::listPathLibrary();

		if ($Rutas == false) {
			$response->getBody()->write(json_encode(array("Error" => "No hay librerias")));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}

		for ($i = 0; $i < count($Rutas) ; $i++) { 

			$nuevaRuta = "";

			$ext = pathinfo($Rutas[$i]->Ruta, PATHINFO_EXTENSION);

			if ($ext == "mp3") {

				$nuevaRuta = dirname($Rutas[$i]->Ruta);
				$libraries = Explorador::ListarDirectorio($nuevaRuta);

				for ($j = 0; $j < count($libraries) ; $j++) { 

					$Cancion= CancionController::AddCancion(
						$Rutas[$i]->idLibreria,
						$libraries[$j]["hash"],
						$libraries[$j]["filename"]
					);
					$MetaDato= MetaDatoController::addMetaDato(
						$libraries[$j]["hash"],
						$libraries[$j]['metaData']['Artista'],
						$libraries[$j]['metaData']['Titulo'],
						$libraries[$j]['metaData']['Album'],
						$libraries[$j]['metaData']['Track'],
						$libraries[$j]['metaData']['Genero'],
						$libraries[$j]['metaData']['Anio']
					);
				}

				array_push($lista, $libraries);
				

			}else{
				$libraries = Explorador::ListarDirectorio($Rutas[$i]->Ruta);

				for ($j = 0; $j < count($libraries) ; $j++) { 

					$Cancion= CancionController::AddCancion(
						$Rutas[$i]->idLibreria,
						$libraries[$j]["hash"],
						$libraries[$j]["filename"]
					);
					$MetaDato= MetaDatoController::addMetaDato(
						$libraries[$j]["hash"],
						$libraries[$j]['metaData']['Artista'],
						$libraries[$j]['metaData']['Titulo'],
						$libraries[$j]['metaData']['Album'],
						$libraries[$j]['metaData']['Track'],
						$libraries[$j]['metaData']['Genero'],
						$libraries[$j]['metaData']['Anio']
					);
				}
				array_push($lista, $libraries);
			}
			
		}
		//$libraries = Explorador::ListarDirectorio('/home/pordefecto/Música/Rap');
		//var_dump($Rutas[0]->idLibreria);
		//die();
		$response->getBody()->write(json_encode($lista));		
		return $response
		->withHeader('content-type', 'application/json')
		->withStatus(200);

	}

}

?>