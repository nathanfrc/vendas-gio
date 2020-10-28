<?php

namespace App\Controllers\Validacao;

use \App\Controllers\Validacao\ResultadoValidacao;


class ImgValidador extends ResultadoValidacao{

    public function validarCadastro()
    {

        $error = false;

        //validação de imagens 
        if(!isset($_REQUEST['foto']))
        {
            $foto = $_FILES['foto'];

           // var_dump($_FILES);

            if(empty($foto['name']))
            {
              self::addErro("Foto: Este campo não pode ser vazio");
              $error = true;
            }else{
                $dimensoes = @getimagesize($foto["tmp_name"]);

        

                if($dimensoes[0] > IMG_LARGURA)
                {
                    self::addErro("Foto: A largura da imagem não deve ultrapassar ".IMG_LARGURA." pixels");
                    $error = true;
                }
                // Verifica se a altura da imagem é maior que a altura permitida
                if($dimensoes[1] >IMG_ALTURA)
                {
                    self::addErro("Foto: Altura da imagem não deve ultrapassar ".IMG_ALTURA." pixels");
                    $error = true;
                }
    
                // Verifica se o tamanho da imagem é maior que o tamanho permitido

                if($foto["size"] > IMG_TAMANHO)
                {
                    self::addErro(" Foto: A imagem deve ter no máximo ".IMG_TAMANHO." bytes");
                    $error = true;
                }

            }

            if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"]))
            {
                self::addErro("Foto: Extensão da imagem não permitida image,pjpeg,jpeg,png,gif e bmp");
                $error = true;
            } 

          
          //  exit;

            if($error){

                return false;
            }

              return $foto;


        }else{

            return false;
        }



    }
}