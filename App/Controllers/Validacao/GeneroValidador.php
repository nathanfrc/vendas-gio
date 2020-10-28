<?php

namespace App\Controllers\Validacao;

use \App\Controllers\Validacao\ResultadoValidacao;


class GeneroValidador extends ResultadoValidacao{

    public function validarGeneroCadastro()
    {


        if(empty($_REQUEST['titulo']))
        {
            self::addErro("Titulo: Este campo não pode ser vazio");
        }
        
        if(\strlen($_REQUEST['titulo']) > 250)
        {
            self::addErro("Titulo: Este campo não pode ter mais que 250 caracteres");
        }

    }
}