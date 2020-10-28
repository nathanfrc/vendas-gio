<?php

namespace App\Models\DAO;

use \App\Lib\DataBase\DataBase;

class FilmesDAO extends DataBase
{

    /**
     * Lista de filmes para mostrar em programação
     */

    public function listarFilmes($filtros = false)
    {

        try{

            $query ="SELECT 
            sessoes.id, sessoes.code, sessoes.titulo, sessoes.`data` as data_sessao, sessoes.hora_inicio, sessoes.hora_fim,
            sessoes.duracao, sessoes.ativo,sessoes.link_direcionamento as link_pagamento,
            
            `local`.id AS local_id, `local`.titulo AS local_evento, `local`.endereco AS local_endereco,
            `local`.total_veiculos AS local_total_veiculos, `local`.total_pessoas AS local_total_pessoas,
            
            
            filmes.id AS filme_id, filmes.titulo AS filme_titulo, filmes.descricao AS filme_descricao,
            filmes.diretor AS filme_diretor, filmes.elenco AS filme_elenco, filmes.dublado AS filme_dublado,
            filmes.fornecedor AS filme_fornecedor, filmes.link_video AS filme_treiler, filmes.duracao AS filme_duracao,
            filmes.classificacao AS filme_classificacao, filmes.caminho_img AS filme_caminho,
            
            generos.id AS filme_id_genero, generos.titulo AS filme_genero_titulo,
            case 
					WHEN filmes.dublado = 'Y' then 'Dublado'
                    when filmes.dublado = 'O' then 'Original'
					ELSE 'Legendado'
				END AS 'dublado'
            
            FROM sessoes
            
            LEFT JOIN `local` ON `local`.id = sessoes.fk_local
            LEFT JOIN filmes ON filmes.id = sessoes.fk_filme
            LEFT JOIN generos ON generos.id = filmes.fk_genero
            WHERE  date_format(sessoes.`data`, '%Y%m%d') >= DATE_FORMAT(NOW(),'%Y%m%d') and filmes.ativo = '1' ";

            if($filtros === false)
            {
                $values = false;

            }else{

                if(isset($filtros['genero']) && is_numeric($filtros['genero']) )
                {
                    $query .=" and generos.id = ? ORDER BY sessoes.data";
                    $values['filme'] = $filtros['genero'];

                }else if(isset($filtros['id_sessao']) && is_numeric($filtros['id_sessao']))
                {
                    $query .=" and sessoes.id = ? ORDER BY sessoes.data";
                    $values['id_sessao'] = $filtros['id_sessao'];
                    
                }else{

                    $query .= " ORDER BY sessoes.data";
                }

            }
            
            if(($r_filmes = $this->query_db($query,$values)) !== false)
            {
                $dados['filmes'] =  $r_filmes;

                return $dados;
            }

           return false;

    }catch(Exception $e)
    {
        echo $e->getMessage();
    }
        
    }

    public function listar($filtros = false)
    {

        try{

            $query ="SELECT generos.titulo as genero, filmes.id, filmes.titulo,filmes.fornecedor, filmes.duracao, filmes.classificacao, filmes.ativo,
            filmes.fk_genero,filmes.descricao,filmes.diretor,filmes.elenco,filmes.caminho_img,filmes.duracao,filmes.link_video,
            case 
					WHEN filmes.dublado = 'Y' then 'Dublado'
                    when filmes.dublado = 'O' then 'Original'
					ELSE 'Legendado'
				END AS 'dublado' 
                        FROM filmes inner join generos on generos.id = filmes.fk_genero  ";

            if($filtros === false)
            {
                $values = false;
                $query .= '  ORDER BY titulo asc ';

            }else{
                
                $query .= "where filmes.id = ?";
                $values['id'] = $filtros['id'];
                
            }
            
            if(($r_filmes = $this->query_db($query,$values)) !== false)
            {
                $dados['filmes'] =  $r_filmes;

               // var_dump($dados);

                return $dados;
            }

           return false;

    }catch(Exception $e)
    {
        echo $e->getMessage();
    }
        
    }

    

     /**
     * Chama a procedure de limpa reservas não efetuadas 
     */

    public function callProcedureExperitAndFindFilmesBySession($filtros =false)
    {
        try{

            //executa procedura de limpa sessões travadas
            $rs =  $this->db_procedure('CALL insert_delete_ingresso_sessoes();');

            $query ="SELECT 
            fileira_numeral.fileira,
            #reversas_ingresso_sessoes.fk_sessoes AS sessao,
            reversas_ingresso_sessoes.pagamento, 
            reversas_ingresso_sessoes.adicionado,
            
            CASE
                WHEN ((reversas_ingresso_sessoes.pagamento IS NULL) AND (reversas_ingresso_sessoes.adicionado IS NULL)) THEN 'DISPONIVEL'
                WHEN ((reversas_ingresso_sessoes.pagamento = '1') AND (reversas_ingresso_sessoes.adicionado IS NOT NULL)) THEN 'VENDIDO'
                WHEN (( DATE_ADD(reversas_ingresso_sessoes.adicionado, INTERVAL 2 MINUTE) < NOW() )) THEN 'DISPONIVEL'
                ELSE 'RESERVADO'
            END AS 'status'
                    
            FROM
            fileira_numeral
            
            LEFT JOIN reversas_ingresso_sessoes ON reversas_ingresso_sessoes.fk_fileira_numeral = fileira_numeral.id
            LEFT JOIN sessoes ON sessoes.id = reversas_ingresso_sessoes.fk_sessoes

               
           ";

            if($filtros === false)
            {
                $values = false;
                $query .= " ORDER BY fileira_numeral.id ASC";

            }else{
                
                $query .= " where sessoes.id = ? ORDER BY fileira_numeral.id ASC";
                $values['id'] = $filtros;
                
            }
            
            if(($r_filmes = $this->query_db($query,$values)) !== false)
            {
                $dados['vagas'] =  $r_filmes;
                return $dados;
            }

            return false;

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }

    }
  
    public function atualizarFilme($id,$form)
    {
        try{

          $form = $this->converterTextoUtf_decode($form);

           $form =  $this->cleanRequest($form);

                
            $tabela['tabela'] = "filmes";
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