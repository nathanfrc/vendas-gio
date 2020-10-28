<?php

namespace App\Lib\DataBase;

use App\Lib\DataBase\Conexao;

//use App\Lib\System\FunctionsFramework;

abstract class DataBase
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::getConnection();
    }

    public function query_db($sql,$params=false) 
    {
       try{
                if(!empty($sql))
                {
                    if($params==false)
                    {
                        $rs =  $this->conexao->query($sql);
                        unset($sql);
                        unset($param);

                    return  $retorno = $this->array_db($rs);
                    }else{

                        foreach($params as $p)
                        {
                            $param[] = $p;
                        }

                        $stmt = $this->conexao->prepare($sql);
                        $rs =  $stmt->execute($param);
            
                        return $retorno = $this->array_db($stmt);
                    }
                
                }
     }catch(Exception $e)
     {
        new \App\Lib\System\LogError($e->getMessage(),'class Database - query_db');
     }

    }

    public function add_db($table, $values) 
    {
        try{

            if(!empty($table['tabela']) &&  !empty($values))
            {
                $table = $table['tabela'];
                foreach($values as $key =>$r)
                {
                    $colunas .= trim(str_replace(":", ",", $key));
                    $json_param = trim(str_replace(":", "", $key));
                    $json["$json_param"] = $json_param;
                    $parametros .= $key.",";
                }


                $sizeParam = (strlen($parametros) -1);
                $parametros = trim(substr($parametros,0,$sizeParam));

                $sizecolunas = strlen($parametros);
                $colunas = substr($colunas,1,$sizeParam);
            
                $stmt = $this->conexao->prepare("INSERT INTO $table ($colunas) VALUES ($parametros)");


                $stmt->execute($values);

                $query='select * from '.$table.' order by id desc limit 1';
                $query_retorno = $this->query_db($query);


                $user_id = 'NULL';
        
                $mq_key ="";
        
                $query = "	INSERT INTO log_geral (uid,fid,tabela,mq_key,action,request_uri,comando) 
                            VALUES (".$user_id.", '".$query_retorno[0]['id']."', '".$table."',
                            '".$mq_key."', 'add', '".$_SERVER['REQUEST_URI']."', '"
                            .json_encode(App\Lib\System\FunctionsFramework::utf8_encode_array($json))."')";

                $this->conexao->query($query);
                

            return $query_retorno;

        }else{

            return false;
        }

     }catch(Exception $e)
     {
        new \App\Lib\System\LogError($e->getMessage(),'class Database - add_db');
     }


    }

    public function update($table, $cols, $values, $where=null) 
    {
        if(!empty($table) && !empty($cols) && !empty($values))
        {
            if($where)
            {
                $where = " WHERE $where ";
            }

            $stmt = $this->conexao->prepare("UPDATE $table SET $cols $where");
            $stmt->execute($values);

            return $stmt->rowCount();

        }else{
            return false;
        }
    }
    
    public function delete($table, $where=null) 
    {
        if(!empty($table))
        {
            /*
                DELETE usuario WHERE id = 1
            */

            if($where)
            {
                $where = " WHERE $where ";
            }

            $stmt = $this->conexao->prepare("DELETE FROM $table $where");
            $stmt->execute();

            return $stmt->rowCount();
        }else{
            return false;
        }
    }


    public function muda_db($banco)
    {
        try{

            if(!empty($banco))
            {
                $sql = 'use '.$banco.";";
                $rs =  $this->conexao->query($sql);
            
                return true;
            }

        }catch(Exception $e)
        {
            new \App\Lib\System\LogError($e->getMessage(),'class Database - muda_db');
        }
    }


    public function update_db($dados,$form)
    {

        try{
		
        if (("$dados[t_unico]" == "numerico") && (!is_numeric("$dados[v_unico]")))
        {
            echo "SECURITY - Valor único não é numérico $dados[t_unico]";
        }
        //$this->erro_db("SECURITY - Valor único não é numérico $dados[t_unico]"); 
		// monta a query para update

       $inputs = [];
        foreach ($form as $rotulo => $info)
        {

            if ($info == "")
            { 
                $query1 .= $rotulo.' = NULL, '; 
            }
			else {

              $query1 .= "`".$rotulo."` = :" . $rotulo . ", ";
              
               $name_array =':'.$rotulo;
               $inputs["$name_array"] = $info;

        
            }
        }


        $query ='update '.$dados['tabela'].' set ';

        $query.=substr("$query1", 0, -2);

        $query.=" where ".$dados['c_unico']." = :v_unico limit 1";

        $inputs[':v_unico'] = $dados['v_unico'];
    
        if ($dados['debug']==true)
        { 
            echo $query; 
        }


        $stmt = $this->conexao->prepare($query);
        $stmt->execute($inputs);

   
		// monta a query retorno de resultado
		$query_retorno="select * from ".$dados['tabela']." where ".$dados['c_unico']." = :v_unico limit 1";
		// realiza o select
		$stm = $this->conexao->prepare($query_retorno);
        // retorna os resultados em um array associativo
        $retorno =  $stm->execute(array(
             'v_unico'=>$dados['v_unico']
         ));

         $retorno =  $stm->fetch();

		// transforma em json e insere no banco
        // if (!is_numeric($user_id))
        // { 
        //     $user_id = 'NULL'; 
        // }

        if(defined('USUARIO_WEBSERVER') && !empty(USUARIO_WEBSERVER)) {

            $user_id = USUARIO_WEBSERVER;

        }else {

            $user_id = 'NULL';

        }

        //$_COOKIE['mq_key'] ="432443535636456";

        $mq_key = bin2hex(openssl_random_pseudo_bytes(16));

		$query = "	INSERT INTO log_geral (uid,fid,tabela,mq_key,action,request_uri,comando) 
                    VALUES (".$user_id.", '".$retorno['id']."', '".$dados['tabela']."', '"
                    .$mq_key."', 'update', '".$_SERVER['REQUEST_URI']."', '"
                    .json_encode(\App\Lib\System\FunctionsFramework::utf8_encode_array($form))."')";
            
         $this->conexao->query($query);

         return $stmt->rowCount();

        }catch(Exception $e)
        {
            new \App\Lib\System\LogError($e->getMessage(),'class Database - update_db');
        }
    }
    

    public function array_db($rs)
    {

        $retorno = false;
        $i=0;
        while ($row = $rs->fetch(\PDO::FETCH_ASSOC))
        {    
          $retorno[$i] = $row;
          ++$i;
        }

        return $retorno;
    }

    public function result_quantidade($result) {

        if(is_array($result)) {

            $cont = count($result);

        }else {

            $cont = false;

        }
        
        return $cont;
        
	}


    public function query_db_two($sql,$params=false)  {

        if(!empty($sql))
        {
            if($params==false)
            {
                $rs =  $this->conexao->quote($sql);

                unset($sql);
                unset($param);

                return $retorno = $this->array_db($rs);

            }else{

                foreach($params as $p)
                {
                    $param[] = $p;
                }


                $stmt = $this->conexao->prepare($sql);
                $rs =  $stmt->execute($param);

                return $retorno = $this->array_db($stmt);
            }           

        }
    }


    //FUN��O QUE INSERE UMA INFORMA��O NO BANCO DE DADOS BASEADO EM ARRAY
    public function insertBd($arr){
        
        $certo = true;
        $nome_tabela = $arr['nome_tabela'];
        $id = $arr['id'];
        $query = "INSERT INTO `$nome_tabela` VALUES ($id";
        
        foreach ($arr as $key => $value) {
            $nome = $key;
            $valor = $value;
            if($nome == 'nome_tabela' || $nome == 'id')
                continue;
            if($value == ''){
                $certo = false;
                break;
            }
            $query.=",?";
            $parametros[] = $value;
        }

        $query.=")";

        if($certo == true){

            $sql = $this->conexao->prepare($query);
            $sql->execute($parametros);

        }
        return $certo;
    }   


    /**
     * teste passar parametros em : antes das arrays
     * 
     * 
     */

    public function add_db_pdo($table, $values) 
    {

        if(!empty($table['tabela']) && !empty($values))
        {
            $table = $table['tabela'];
            foreach($values as $key =>$r)
            {
              
                $colunas .= ','.$key;
                $parametros .= ":".$key.",";
                $valores[":$key"] = $r;
                $json["$key"] = $r;
        
            }
        
            $sizeParam = (strlen($parametros) -1);
            $parametros = trim(substr($parametros,0,$sizeParam));
        
            $sizecolunas = strlen($parametros);
            $colunas = substr($colunas,1,$sizeParam);
        
            $stmt = $this->conexao->prepare("INSERT INTO $table ($colunas) VALUES ($parametros)");

            $stmt->execute($valores);

            $query='select * from '.$table.' order by id desc limit 1';
            $query_retorno = $this->query_db($query);


            // transforma em json e insere no banco
            // if (!is_numeric($user_id))
            // { 
            //     $user_id = 'NULL'; 
            // }

         /*   if(defined('USUARIO_WEBSERVER') && !empty(USUARIO_WEBSERVER)) {

                $user_id = USUARIO_WEBSERVER;
    
            }else {
    
                $user_id = 'NULL';
    
            }

            $mq_key = bin2hex(openssl_random_pseudo_bytes(16));
    
            $query = "	INSERT INTO log_geral (uid,fid,tabela,mq_key,action,request_uri,comando) 
                        VALUES (".$user_id.", '".$query_retorno[0]['id']."', '".$table."',
                         '".$mq_key."', 'add', '".$_SERVER['REQUEST_URI']."', '"
                         .json_encode(App\Lib\System\FunctionsFramework::utf8_encode_array($json))."')";*/


            $this->conexao->query($query);
            

           // return $stmt->rowCount();
           return $query_retorno;

        }else{

            return false;
        }
    }

    public function add_db_two($table, $values) 
    {

        if(!empty($table['tabela']) &&  !empty($values))
        {
            $table = $table['tabela'];
            foreach($values as $key =>$r)
            {
              
                $colunas .= ','.$key;
                $parametros .= ":".$key.",";
                $valores[":$key"] = $r;
                $json["$key"] = $r;
        
            }
        
            $sizeParam = (strlen($parametros) -1);
            $parametros = trim(substr($parametros,0,$sizeParam));
        
            $sizecolunas = strlen($parametros);
            $colunas = substr($colunas,1,$sizeParam);
        
            $stmt = $this->conexao->prepare("INSERT INTO $table ($colunas) VALUES ($parametros)");

            $stmt->execute($valores);

            $query='select * from '.$table.' order by id desc limit 1';
            $query_retorno = $this->query_db($query);


            // transforma em json e insere no banco
            // if (!is_numeric($user_id))
            // { 
            //     $user_id = 'NULL'; 
            // }

            if(defined('USUARIO_WEBSERVER') && !empty(USUARIO_WEBSERVER)) {

                $user_id = USUARIO_WEBSERVER;
    
            }else {
    
                  /*  $query = "select id from cadastro_colaborador  where login = ?";
                    $values['login'] = defined("USUARIO_LOGIN_WEBSERVICE") ? USUARIO_LOGIN_WEBSERVICE : "falha.webservice" ;
                    if(($login_db = $this->query_db($query,$values)) !== false)
                    {
                        $user_id = $login_db[0]['id'];

                    }else{*/

                        $user_id = null;
                   // }*/

                   /* $login =  defined("USUARIO_LOGIN_WEBSERVICE") ? USUARIO_LOGIN_WEBSERVICE : "falha.webservice" ;
                    $sql = "select id from cadastro_colaborador  where login = '".$login."'";
   
                   $rs = $this->conexao->query($sql);
   
                   $retorno = $this->array_db($rs);
   
                   $user_id =  $retorno[0]['id'];*/
    
            }

            $mq_key = bin2hex(openssl_random_pseudo_bytes(16));
    
           /* $query = "	INSERT INTO log_geral (uid,fid,tabela,mq_key,action,request_uri,comando) 
                        VALUES (".$user_id.", '".$query_retorno[0]['id']."', '".$table."',
                         '".$mq_key."', 'add', '".$_SERVER['REQUEST_URI']."', '"
                         .json_encode(\App\Lib\System\FunctionsFramework::utf8_encode_array($json))."')";*/


            $this->conexao->query($query);
            

           // return $stmt->rowCount();
           return $query_retorno;

        }else{

            return false;
        }
    }

    
    public function cleanRequest($array):array
    {

        try{
            if(isset($array['url']))
            {
                unset($array['url']);
            }
        
            if(isset($array['submit']))
            {
                unset($array['submit']);
            }

            return $array;

        }catch(Exception $e)
        {
          echo  $e->getMessage();
        }

    }


    public function db_procedure($call)
    {
        try{

            $rs =  $this->conexao->query($call);

             return $rs;

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    
    public function converterTextoUtf_decode($form)
    {

        $newArray =[];
        foreach($form as $ky=>$f)
        {
            $newArray[$ky] = \utf8_encode($f);
        }
        return$newArray;
    }





}