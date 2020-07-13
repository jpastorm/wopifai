<?php
namespace App\Config;
use PDO;
class Database{
    private $dbHost='localhost';
    private $dbUser='admin';
    private $dbPass='password';
    private $dbName='musica';

    //connection

    public function connectDB(){
        $mysqlConnect="mysql:host=$this->dbHost;dbname=$this->dbName";
        $dbConnection=new PDO($mysqlConnect,$this->dbUser,$this->dbPass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }

}
?>
