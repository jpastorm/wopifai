<?php
namespace App\Config;
use PDO;
class Database{
    private $dbHost='localhost';
    private $dbUser='root';
    private $dbPass='root';
    private $dbName='wopifai';

    //connection

    public function connectDB(){
        $mysqlConnect="mysql:host=$this->dbHost;port=8889;dbname=$this->dbName";
        $dbConnection=new PDO($mysqlConnect,$this->dbUser,$this->dbPass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }

}
?>