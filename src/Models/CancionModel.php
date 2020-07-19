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
      
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      return '{"error":{"text":'.$e->getMessage().'}}';
    }
  }
  public function checkHash(){

    $idCancion = $this->idCancion;

    $db = new \App\Config\Database;

    $sql="SELECT idCancion FROM Cancion WHERE idCancion=:idCancion";
    try{
      $db = $db->connectDB();

      $result = $db->prepare($sql);
      $result->bindParam(':idCancion',$idCancion);
      $result->execute();
      if ($result->rowCount() > 0) {
        //$cancion=$result->fetchAll(PDO::FETCH_OBJ);
        //return json_encode($cancion);
        return true;
      }else{
        return false;
      }
      $result = null;
      $db = null;
    }catch(PDOException $e){
      return '{"error":{"text":'.$e->getMessage().'}}';
    }
  }

  public function getPath(){
    $idCancion = $this->idCancion;

    $db = new \App\Config\Database;

    $sql="select Libreria.Ruta,Cancion.NombreArchivo from Libreria inner join Cancion on Libreria.idLibreria=Cancion.idLibreria where Cancion.idCancion=:idCancion";
    try{
      $db = $db->connectDB();

      $result = $db->prepare($sql);
      $result->bindParam(':idCancion',$idCancion);
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
  public function buscador($nombre){
    $nombre="'".$nombre." *'";
    $db = new \App\Config\Database;

    $sql="select Cancion.nombreArchivo,MetaDato.Artista,MetaDato.Titulo FROM MetaDato inner join Cancion 
on MetaDato.idCancion = Cancion.idCancion WHERE MATCH(Artista,Titulo)AGAINST (:nombre  IN BOOLEAN MODE)";
    try{
      $db = $db->connectDB();

      $result = $db->prepare($sql);
      $result->bindParam(':nombre',$nombre);
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
  
    public function getInformation($idCancion){

    $db = new \App\Config\Database;

    $sql="select Cancion.idCancion,Cancion.idLibreria,Libreria.Nombre,MetaDato.Artista,MetaDato.Titulo,MetaDato.Album,MetaDato.Track,MetaDato.Genero,MetaDato.Anio from Cancion 
inner join Libreria on Cancion.idLibreria = Libreria.idLibreria
inner join MetaDato on MetaDato.idCancion = Cancion.idCancion where Cancion.idCancion = :idCancion";
    try{
      $db = $db->connectDB();

      $result = $db->prepare($sql);
      $result->bindParam(':idCancion',$idCancion);
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

    public function findSong(){

    $idCancion = $this->idCancion;

    $db = new \App\Config\Database;

    $sql="select Cancion.idCancion,Cancion.nombreArchivo,Libreria.Ruta FROM Cancion 
inner join Libreria on Libreria.idLibreria=Cancion.idLibreria
WHERE idCancion=:idCancion";
    try{
      $db = $db->connectDB();

      $result = $db->prepare($sql);
      $result->bindParam(':idCancion',$idCancion);
      $result->execute();
      if ($result->rowCount() > 0) {
        $cancion=$result->fetchAll(PDO::FETCH_OBJ);
        return json_encode($cancion);
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