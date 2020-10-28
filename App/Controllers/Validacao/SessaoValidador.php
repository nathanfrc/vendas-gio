<?php

namespace App\Controllers\Validacao;

use \App\Controllers\Validacao\ResultadoValidacao;


class SessaoValidador extends ResultadoValidacao{

    public function validarCadastro($update = false)
    {

       // var_dump($_REQUEST);

        if(empty($_REQUEST['local']))
        {
            self::addErro("Local: Este campo n�o pode ser vazio");

        }else{

            //validar se local existe
            $locaisDAO = new \App\Models\DAO\LocalDAO();

            $filtro['id'] = $_REQUEST['local'];

            if(($dadosLocais =  $locaisDAO->listar($filtro)) === false)
            {
                self::addErro("Local: N�o existe na base de dados");
            }

           
        }
        
        if(empty($_REQUEST['filme']))
        {
            self::addErro("Filme: Este campo n�o pode ser vazio");

        }else{

            //validar se filme existe 

            $filmeDAO = new \App\Models\DAO\FilmesDAO();

            $filtro['id'] = $_REQUEST['filme'];

            if(($dadosFilmes =  $filmeDAO->listar($filtro)) === false)
            {
                self::addErro("Filme: N�o existe na base de dados");
            }

         

        }


        if($update === false)
        {
            if(empty($_REQUEST['code']))
            {
                self::addErro("Code: Este campo n�o pode ser vazio");
    
            }else{
    
                $sessao = new \App\Models\DAO\SessaoDAO();
    
               if(($sessao->findByCode($_REQUEST['code'])) !== false  )
               {
                  self::addErro("Code: J� existe code cadastrado!");
               }
    
            }


        }

        if(empty($_REQUEST['data']))
        {
            self::addErro("Data: Este campo n�o pode ter mais que 11 caracteres");
        }

        if(empty($_REQUEST['hora_inicio']))
        {
            self::addErro("Hora inicio: Este campo n�o pode ser vazio");
        }
        
        if(empty($_REQUEST['hora_fim']) > 11)
        {
            self::addErro("Hora fim : Este campo n�o pode ser vazio");
        }

    }



    public function validaSessao($id_sessao)
    {

         if(empty($id_sessao))
         {
            self::addErro("Id Sessao : Este campo n�o pode ser vazio");

         }else{

                //validar se existe
              $sessaoDAO =  new \App\Models\DAO\SessaoDAO;

                $filtro['id'] = $id_sessao;
            

                if(($sessaoDAO->listar($filtro)) === false)
                {
                    self::addErro("Id Sessao : N�o existe na base de dados");
                }

         }



    }
}