<?php

namespace App\Models\DAO;

use \App\Lib\DataBase\DataBase;

class GenericDAO extends DataBase
{

    /**
     * Lista de filmes para mostrar em programação
     */

    public function listar($filtros = false)
    {

        try{

            $query ="SELECT * FROM local ";

            if($filtros === false)
            {
                $values = false;
                $query .= "order by titulo asc";

            }else{

                $query .= "where id = ?";
                $values['id'] = $filtros['id'];
            }
            
            if(($r_generos = $this->query_db($query,$values)) !== false)
            {
                $dados['locais'] =  $r_generos;


              //  var_dump($dados);

                return $dados;
            }

           return false;

    }catch(Exception $e)
    {
        echo $e->getMessage();
    }
        
    }


    public function create($tabela,$form)
    {
        try{

            if(empty($tabela) or empty($form))
            {
                return false;
            }

           $form =  $this->cleanRequest($form);


           $form = $this->converterTextoUtf_decode($form);

            $table['tabela'] = $tabela;
            $retorno = $this->add_db_pdo($table,$form);
    
            if(isset($retorno[0]['id']))
            {
                return true;
            }

            return false;

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function atualizarGenero($id,$form)
    {
        try{

                
            $tabela['tabela'] = "generos";
            $tabela['v_unico'] = $id;//FORM 
            $tabela['c_unico'] = 'id'; //
            $tabela['t_unico'] = 'numerico';

            $retorno	= $this->update_db($tabela,$form);

          //  var_dump($retorno);

            if(is_numeric($retorno))
            {
                return true;
            }

            return false;

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }



 
}