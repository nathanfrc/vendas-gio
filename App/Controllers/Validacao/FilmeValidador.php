<?php

namespace App\Controllers\Validacao;

use \App\Controllers\Validacao\ResultadoValidacao;


class FilmeValidador extends ResultadoValidacao{

    public function validarCadastro()
    {


        if(empty($_REQUEST['titulo']))
        {
            self::addErro("Titulo: Este campo n�o pode ser vazio");
        }
        
        if(\strlen($_REQUEST['titulo']) > 250)
        {
            self::addErro("Titulo: Este campo n�o pode ter mais que 250 caracteres");
        }


        if(empty($_REQUEST['genero']))
        {
            self::addErro("Genero: Este campo n�o pode ser vazio");

        }else{

              $generoDAO = new \App\Models\DAO\GeneroDAO();

              $filtro['id'] = $_REQUEST['genero'];

            if(($dadosGeneros= $generoDAO->listarGerenos($filtro)) ===false)
            {
                self::addErro("Genero: N�o existe na base de dados");

            }else{

                    $_REQUEST['fk_genero'] = $_REQUEST['genero'];
                    unset($_REQUEST['genero']);
            }
        }
        
        //dura��o
        if(empty($_REQUEST['duracao']))
        {
            self::addErro("Duracao: Este campo n�o pode ser vazio");
        }
        
        if(\strlen($_REQUEST['duracao']) > 50)
        {
            self::addErro("Duracao: Este campo n�o pode ter mais que 11 caracteres");
        }

        //classificacao
        if(empty($_REQUEST['classificacao']))
        {
            self::addErro("Classificacao: Este campo n�o pode ser vazio");
        }
        
        if(\strlen($_REQUEST['classificacao']) > 50)
        {
            self::addErro("Classificacao: Este campo n�o pode ter mais que 11 caracteres");
        }



        if(isset($_REQUEST['diretor']))
        {
            if(\strlen($_REQUEST['diretor']) > 250)
            {
                self::addErro("Diretor: Este campo n�o pode ter mais que 11 caracteres");
            }
        }

        if(isset($_REQUEST['elenco']))
        {
            if(\strlen($_REQUEST['elenco']) > 250)
            {
                self::addErro("Elenco: Este campo n�o pode ter mais que 11 caracteres");
            }
        }

        if(isset($_REQUEST['link_video']))
        {
            if(\strlen($_REQUEST['link_video']) > 250)
            {
                self::addErro("Link Video: Este campo n�o pode ter mais que 11 caracteres");
            }
        }

        //valida��o de imagens 
        if(!isset($_REQUEST['foto']))
        {
            $foto = $_FILES['foto'];

            if(!empty($foto['name']))
            {
              self::addErro("Foto : Este campo n�o pode ser vazio");
            }

            if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"]))
            {
                self::addErro("Foto : Extens�o da imagem n�o permitida");
            } 

            $dimensoes = getimagesize($foto["tmp_name"]);

            if($dimensoes[0] > IMG_LARGURA)
            {
                self::addErro("A largura da imagem n�o deve ultrapassar ".IMG_LARGURA." pixels");
            }
            // Verifica se a altura da imagem � maior que a altura permitida
            if($dimensoes[1] >IMG_ALTURA)
            {
                self::addErro("Altura da imagem n�o deve ultrapassar ".IMG_ALTURA." pixels");
            }

            // Verifica se o tamanho da imagem � maior que o tamanho permitido
            if($foto["size"] > IMG_TAMANHO)
            {
                self::addErro("A imagem deve ter no m�ximo ".IMG_TAMANHO." bytes");
            }
            
        }



    }
}