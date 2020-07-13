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

  public function listPathLibrary() {
    //$db = new Database();
    $db = new \App\Config\Database;
    $sql ="SELECT idLibreria,Ruta FROM Libreria";
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

  public function listContentId(){

    $idLibreria=$this->idLibreria;

    $db = new \App\Config\Database;

    $sql="select Cancion.nombreArchivo,
    MetaDato.idCancion,
    MetaDato.Artista,
    MetaDato.Titulo,
    MetaDato.Album,
    MetaDato.Track,
    MetaDato.Genero,
    MetaDato.Anio from Cancion 
    inner join MetaDato on MetaDato.idCancion = Cancion.idCancion
    where idLibreria=:idLibreria";
    try{
      $db = $db->connectDB();

      $result = $db->prepare($sql);
      $result->bindParam(':idLibreria',$idLibreria);
      $result->execute();
      if ($result->rowCount() > 0) {
        $cancion = $result->fetchAll(PDO::FETCH_OBJ);
        return $cancion;        
      }else{
        return false;
      }
      $result = null;
      $db = null;
    }catch(PDOException $e){
      return '{"error":{"text":'.$e->getMessage().'}}';
    }
  }
  public function listContent(){

    $idLibreria=$this->idLibreria;

    $db = new \App\Config\Database;

    $sql="select Cancion.nombreArchivo,
    MetaDato.idCancion,
    MetaDato.Artista,
    MetaDato.Titulo,
    MetaDato.Album,
    MetaDato.Track,
    MetaDato.Genero,
    MetaDato.Anio from Cancion 
    inner join MetaDato on MetaDato.idCancion = Cancion.idCancion";
    try{
      $db = $db->connectDB();

      $result = $db->prepare($sql);      
      $result->execute();
      if ($result->rowCount() > 0) {
        $cancion = $result->fetchAll(PDO::FETCH_OBJ);
        return $cancion;        
      }else{
        return false;
      }
      $result = null;
      $db = null;
    }catch(PDOException $e){
      return '{"error":{"text":'.$e->getMessage().'}}';
    }
  }

}


?>