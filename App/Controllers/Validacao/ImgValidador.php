<?php

namespace App\Controllers\Validacao;

use \App\Controllers\Validacao\ResultadoValidacao;


class ImgValidador extends ResultadoValidacao{

    public function validarCadastro()
    {

        $error = false;

        //valida��o de imagens 
        if(!isset($_REQUEST['foto']))
        {
            $foto = $_FILES['foto'];

           // var_dump($_FILES);

            if(empty($foto['name']))
            {
              self::addErro("Foto: Este campo n�o pode ser vazio");
              $error = true;
            }else{
                $dimensoes = @getimagesize($foto["tmp_name"]);

        

                if($dimensoes[0] > IMG_LARGURA)
                {
                    self::addErro("Foto: A largura da imagem n�o deve ultrapassar ".IMG_LARGURA." pixels");
                    $error = true;
                }
                // Verifica se a altura da imagem � maior que a altura permitida
                if($dimensoes[1] >IMG_ALTURA)
                {
                    self::addErro("Foto: Altura da imagem n�o deve ultrapassar ".IMG_ALTURA." pixels");
                    $error = true;
                }
    
                // Verifica se o tamanho da imagem � maior que o tamanho permitido

                if($foto["size"] > IMG_TAMANHO)
                {
                    self::addErro(" Foto: A imagem deve ter no m�ximo ".IMG_TAMANHO." bytes");
                    $error = true;
                }

            }

            if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"]))
            {
                self::addErro("Foto: Extens�o da imagem n�o permitida image,pjpeg,jpeg,png,gif e bmp");
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