<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\MetaDatoModel;
/**
 * 
 */
class MetaDatoController
{
	
	public function addMetaDato($idCancion,$Artista,$Titulo,$Album,$Track,$Genero,$Anio) 
	{   				
		$MetaDato = new MetaDatoModel();
		$MetaDato->idCancion = $idCancion;
		$MetaDato->Artista   = $Artista;
		$MetaDato->Titulo    = $Titulo;
		$MetaDato->Album     = $Album;
		$MetaDato->Track     = $Track;
		$MetaDato->Genero    = $Genero;
		$MetaDato->Anio      = $Anio;	
		$res = $MetaDato->createMetaDato();
		if ($res) {
			return true;
		}else{
			return false;
		}
	}
}

?>