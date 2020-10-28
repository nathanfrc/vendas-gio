<?php

namespace App\Lib\DataBase;

class Conexao
{
    public static $connection;

    public static function getConnection()
    {
        $pdoConfig  = DB_DRIVER . ":". "host=" . DB_HOST . ";";
        $pdoConfig .= "dbname=".DB_NAME.";charset=utf8";

      //  echo $pdoConfig ."user=".DB_USER." pas".DB_PASSWORD;
        try { 
           
                if(!isset($connection))
                {
                    try{

                        $connection =  new \PDO($pdoConfig, DB_USER, DB_PASSWORD,array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                        
                    } catch(Exception $e)
                    {
                       new \App\Lib\System\LogError($e->getMessage(),'class Conexao - getConnection');
                    }
                }

                return $connection;
            
        } catch (PDOException $e) {
           throw new Exception("Erro de conexão com o banco de dados",500);
        }
    }

}