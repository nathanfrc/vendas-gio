<?php

namespace App\Lib\System;

class LogError{



    public function __construct($msg,$rotina)
    {
        try{

            $linha = date("d-m-Y H:i:s")." - ".$rotina." - ".$msg."\n";

            $fp = fopen(DIR_LOG, "a");
            // Escreve a mensagem passada através da variável $msg
            $escreve = fwrite($fp,  $linha);
            
            // Fecha o arquivo
            fclose($fp);
           
        }catch(Exception $e)
        {
            echo $e->getMessage()." - erro ao gravar log no arquivo log";
        }


    }







}