<?php 
namespace App\Models;
use PDO;

/**
 * 
 */
class MetaDatoModel
{
	public $idMetaDato;
  public $idCancion;
  public $Artista;
  public $Titulo;
  public $Album;
  public $Track;
  public $Genero;
  public $Anio;
  
  public function createMetaDato() {

    $idCancion = $this->idCancion;
    $Artista = $this->Artista;
    $Titulo = $this->Titulo;
    $Album = $this->Album;
    $Track = $this->Track;
    $Genero = $this->Genero;
    $Anio = $this->Anio;

    $db = new \App\Config\Database;
    $sql="INSERT INTO MetaDato(idCancion,Artista,Titulo,Album,Track,Genero,Anio) VALUES(:idCancion,:Artista,:Titulo,:Album,:Track,:Genero,:Anio)";
    try{

      $db = $db->connectDB();
      $resultado = $db->prepare($sql);
      $resultado->bindParam(':idCancion',$idCancion);
      $resultado->bindParam(':Artista',$Artista);
      $resultado->bindParam(':Titulo',$Titulo);
      $resultado->bindParam(':Album',$Album);
      $resultado->bindParam(':Track',$Track);
      $resultado->bindParam(':Genero',$Genero);
      $resultado->bindParam(':Anio',$Anio);
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

    public function getMeta(){
      
    $idCancion = $this->idCancion;
    $db = new \App\Config\Database;

    $sql="select * from MetaDato where idCancion = :idCancion";

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

}


 ?>