<?php

namespace App\Lib\System;

use \App\Models\DAO\LoginDAO;

class Login extends LoginDAO
{

    public function __construct(){}

    public function validarLoginSystem()
    {
        try{

            $loginDAO = new \App\Models\DAO\LoginDAO();

            if(($retorno = $loginDAO->validarLoginDAO()) === false)
            {
                return false;
            }

            return true;

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }

    }

    public function logarSystem($user,$password)
    {
        try{

            //validando dados de login
            $loginDAO = new \App\Models\DAO\LoginDAO();

            if(( $res = $loginDAO->logarDAO($user,$password)) ===false)
            {
                return false;
            }

            return true;

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }



    }



}