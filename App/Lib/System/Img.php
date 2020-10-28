<?php

namespace App\Lib\System;

class Img
{

    public function __construct(){



    }


    public function gravarImagem($foto)
    {
        try{

            // Pega extensão da imagem
            preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

          /* var_dump($foto);

           $file_ext = strtolower(end(explode('.',$foto['name'])));

           var_dump($file_ext);
           exit;*/

            // Gera um nome único para a imagem
            $nome_imagem = md5(uniqid(time())) . "." .  $ext[1];
            // Caminho de onde ficará a imagem
            $caminho_imagem = PATH_REPOSITORIO_IMG . $nome_imagem;

            move_uploaded_file($foto["tmp_name"], $caminho_imagem);

          return $url = PATH_REPOSITORIO_IMG_LINK.$nome_imagem;

          
        }catch(Exception $e)
        {
            echo $e->getMessage();
            return false;

        }
    }

  
}