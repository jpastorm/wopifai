<?php 
namespace App\Models;
use PDO;

/**
 * 
 */
class LibraryModel
{
	public $idLibreria;
	public $Nombre;
	public $Ruta;
	public $fechaCreacion;
	public $fechaUltimoEscaneo;

    public function listLibrary() {
  	//$db = new Database();
    $db = new \App\Config\Database;
    $sql ="SELECT * FROM Libreria";
    try{
      $db = $db->connectDB();
      $result = $db->query($sql);
      if ($result->rowCount() > 0) {
        $library = $result->fetchAll(PDO::FETCH_OBJ);
        return $library;
      }else{
        return false;
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      return '{"error":{"text":'.$e->getMessage().'}}';
    }   
  }
  
  public function createLibrary() {

    $Nombre = $this->Nombre;
    $Ruta = $this->Ruta;
    $db = new \App\Config\Database;
    $sql ="INSERT INTO Libreria(Nombre, Ruta) VALUES(:Nombre,:Ruta)";
    try{

      $db = $db->connectDB();
      $resultado = $db->prepare($sql);
      $resultado->bindParam(':Nombre',$Nombre);
      $resultado->bindParam(':Ruta',$Ruta);
      $res=$resultado->execute();
      if ($res) {
        $lastInsertId = $db->lastInsertId();
        return $lastInsertId;
      }else{
      	return false;
      }
      
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      return '{"error":{"text":'.$e->getMessage().'}}';
    }
  }

}


 ?>