<?php

namespace App\Lib\System;

class Cookie
{

    public function __construct() {}


    public function criarCookie()
    {
        try{

            $func =  new \App\Lib\System\FunctionsFramework;
            $chave = $func->randomkeys(64);

            setcookie ('user',$chave,time()+36000,"/",COOKIE_PATH, TRUE, TRUE);
            return $chave;

        }catch(Exception $e)
        {
            echo $e->getMessage();

        }
    }

    public function logout()
    {
        try{

            setcookie ('user','',time()+36000,"/",COOKIE_PATH, TRUE, TRUE);
            
        }catch(Exception $e)
        {
            echo $e->getMessage();

        }
    }




}