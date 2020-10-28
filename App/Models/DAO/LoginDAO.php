<?php

namespace App\Models\DAO;

use \App\Lib\DataBase\DataBase;

class LoginDAO extends DataBase
{
    
    public function logarDAO($usuario,$senha)
    {

        $query = "	select * from cadastro_cliente 
        where email = ? and senha = md5( ? ) and ativo = '1'";

        $values['email'] = $usuario;
        $values['senha'] = $senha;

        if(($r_login = $this->query_db($query,$values)) === false)
        {
            //echo "login não cadastrado";
            return false;

        }else{

           // echo "sucesso";

              $cookie =  new  \App\Lib\System\Cookie;
              $chave =   $cookie->criarCookie();

              $table['tabela'] = "log_user";

              $form['tipo_cadastro'] = 'ADMIN';
              $form['chave'] = $chave;
              $form['email'] = $r_login[0]['email'];
              $form['senha'] = $r_login[0]['senha'];
              $form['ip'] = $_SERVER['REMOTE_ADDR'];
              $retorno = $this->add_db_pdo($table,$form);
      
              if(isset($retorno[0]['id']))
              {
                  return true;
              }

              return false;

        }
    }


    public function validarLoginDAO()
    {
        if ((isset($_COOKIE['user'])))
        {
            $query = "select * from log_user
                where chave = ? AND ativo = '1' limit 1";

                $values['chave'] = $_COOKIE['user'];
                
            if(($r_login_log = $this->query_db($query,$values)) === false)
            {
                return false;

            }

            $query = "select * from cadastro_cliente where email = ? and senha = ? and ativo = '1' limit 1";
            $valuesCad['login'] = $r_login_log[0]['email'];
            $valuesCad['senha'] = $r_login_log[0]['senha'];

            if(($r_login = $this->query_db($query,$valuesCad)) === false)
            {
                return false;
            }
                $login['login'] = $r_login[0];

                //var_dump($login);
                return $login;
        }else{

            return false;
        }


    }

    public function atualizar($id,$form)
    {
        try{

            $form =  $this->cleanRequest($form);
                
            $tabela['tabela'] = "cadastro_cliente";
            $tabela['v_unico'] = $id;//FORM 
            $tabela['c_unico'] = 'id'; //
            $tabela['t_unico'] = 'numerico';

            $form['senha'] = \md5($form['senha']);

            $form['email'] = $form['usuario'];
            unset($form['usuario']);

           // var_dump($form);

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