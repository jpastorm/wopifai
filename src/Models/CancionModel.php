<?php 
namespace App\Models;
use PDO;

/**
 * 
 */
class CancionModel
{
	public $idCancion;
	public $idLibreria;
	public $NombreArchivo;
  
  public function createCancion() {
    $idCancion = $this->idCancion;
    $idLibreria = $this->idLibreria;
    $NombreArchivo = $this->NombreArchivo;
    $db = new \App\Config\Database;
    $sql="INSERT INTO Cancion(idCancion,idLibreria, NombreArchivo) VALUES(:idCancion,:idLibreria,:NombreArchivo)";
    try{

      $db = $db->connectDB();
      $resultado = $db->prepare($sql);
      $resultado->bindParam(':idCancion',$idCancion);
      $resultado->bindParam(':idLibreria',$idLibreria);
      $resultado->bindParam(':NombreArchivo',$NombreArchivo);
      $res = $resultado->execute();
      if ($res) {        
        return true;
      }else{
      	return false;
      }
      
      $resultado=null;
      $db=null;
    }catch(PDOException $e){
      return '{"error":{"text":'.$e->getMessage().'}}';
    }
  }

}


 ?>