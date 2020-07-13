<?php 
namespace App\Libraries;
/**
 
 */

class Explorador
{
	
	public static function ListarDirectorio($path)
	{
		$lista = array();

		$mp3 = preg_grep('~\.(mp3)$~', scandir($path));

		foreach ($mp3 as $archivo) {

			$totalPath = $path.'/'.$archivo;

			$hash = self::HashFile($totalPath);
			$metaData = self::GetTags($totalPath);
			$datos = array(
				'filename'  => $archivo,
				'hash' 	    => $hash,
				'totalPath' => $totalPath,
				'metaData'  => $metaData
			);

			array_push($lista, $datos);
		}

		return $lista;
	}

	public static function HashFile($filePath)
	{
		return hash_file('sha1', $filePath);
	}

	public static function GetTags($filePath)
	{

		$getID3 = new \getID3;

		$ThisFileInfo = $getID3->analyze($filePath);

		$getID3->CopyTagsToComments($ThisFileInfo);

		$datos = array(			
			'Artista' 					=> $ThisFileInfo['id3v2']['comments']['artist'][0],
			'Titulo' 			    	=> $ThisFileInfo['id3v2']['comments']['title'][0],
			'Album'						=> $ThisFileInfo['id3v2']['comments']['album'][0],
			'Track'						=> $ThisFileInfo['id3v2']['comments']['track_number'][0],
			'Genero'					=> $ThisFileInfo['id3v2']['comments']['genre'][0],
			'Anio'						=> $ThisFileInfo['id3v2']['comments']['year'][0]
		);
		
		return $datos;
	}
	public static function GetAlbumCover($filePath)
	{
		$getID3 = new \getID3;

		$ThisFileInfo = $getID3->analyze($filePath);

		$getID3->CopyTagsToComments($ThisFileInfo);

		////////
		if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
			$cover = $ThisFileInfo['id3v2']['APIC'][0]['data'];
		} elseif (isset($ThisFileInfo['id3v2']['PIC'][0]['data'])) {
			$cover = $ThisFileInfo['id3v2']['PIC'][0]['data'];
		} else {
			$cover = null;
		}
		if (isset($ThisFileInfo['id3v2']['APIC'][0]['image_mime'])) {
			$mimetype = $ThisFileInfo['id3v2']['APIC'][0]['image_mime'];
		} else {
    		$mimetype = 'image/jpeg'; // or null; depends on your needs
		}
		///////

		return array('cover' => $cover,'mimetype' => $mimetype );
	}

}

?>