<?php

namespace App\Models\DAO;

use \App\Lib\DataBase\DataBase;

class DashboardDAO extends DataBase
{
    
    public function dash($empresa,$hoje)
    {
        $dados = [];

        $query = "SELECT COUNT(*) AS total FROM telefonica_os";

        if($hoje === true && $empresa === false)
        {
            $query .= "  WHERE DATE_FORMAT(telefonica_os.adicionado,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')";
        }

        if($empresa !== false)
        {
            $query.=" where empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;

            if($hoje === true)
            {
                $query .= " and DATE_FORMAT(telefonica_os.adicionado,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            }

        }else{

            $values = false;
        }

        if(($r_total_sinistros = $this->query_db($query,$values)) !== false)
        {
            $dados['total_sinistros'] =  $r_total_sinistros[0]['total'];
        }

        //=========================================================================================

        $query = "SELECT COUNT(*) AS total FROM telefonica_os where pagamento = '1'";

        if($hoje === true && $empresa === false)
        {
            $query .= " and  etiqueta = '0'";
        }

        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;

            
            if($hoje === true)
            {
                $query .= "  and  etiqueta = '0'";
            }

        }else{

            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_pagamento'] =  $r_total_pagamento[0]['total'];
        }

        //pagamentos não executados

        $query = "SELECT COUNT(*) AS total FROM telefonica_os where pagamento = '0'";
        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;
        }else{
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_pagamento_executado'] =  $r_total_pagamento[0]['total'];
        }

        //sisnistros recebidos

        $query = "SELECT COUNT(*) AS total FROM telefonica_os where recebimento = '1'";

        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;
        }else{
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_recebimento'] =  $r_total_pagamento[0]['total'];
        }

        
        $query = "SELECT COUNT(*) AS total FROM telefonica_os where expedicao = '1'";

        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;
        }else{
            
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_expedido'] =  $r_total_pagamento[0]['total'];
        }

        //=============================================================

        $query = "SELECT COUNT(*) AS total FROM telefonica_os where objeto_postado = '1'";

        if($hoje === true && $empresa === false)
        {
            $query .= " and  recebimento = '0'";
        }

        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;

            if($hoje === true)
            {
                $query .= " and  recebimento = '0'";
             }
        }else{
            
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_objeto_postado'] =  $r_total_pagamento[0]['total'];
        }

        //=================================================================
        //etiquetas geradas
        $query = "SELECT COUNT(*) AS total FROM telefonica_os where etiqueta = '1'";


        if($hoje === true && $empresa === false)
        {
            $query .= " and  objeto_postado = '0'";
        }

        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;

            if($hoje === true)
            {
                $query .= " and  objeto_postado = '0'";
            }

        }else{
            
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_etiqueta'] =  $r_total_pagamento[0]['total'];
        }


        $query = "SELECT DATE_FORMAT(NOW(),'%d-%m-%Y') AS data_hoje,telefonica_os.empresa_atendimento,
        COUNT(telefonica_os.id) AS quantidade_total_os 
        FROM telefonica_os
        WHERE DATE_FORMAT(telefonica_os.adicionado,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')
        GROUP BY telefonica_os.empresa_atendimento;";


        if(($r_criadas = $this->query_db($query,false)) !== false)
        {
            $dados['criadas'] = $r_criadas;
        }

        return $dados;
    }



    public function statusOS()
    {
        $dados['total_status'];

        $query = "SELECT telefonica_events.idEvent, 
        telefonica_events.descricao, 
        COUNT(telefonica_events.idEvent) AS num_os 
        FROM telefonica_events
        GROUP BY telefonica_events.idEvent";

        if(($r_status = $this->query_db($query,$values)) !== false)
        {
            $dados['total_status'] = $r_status;
        }

        return $dados;

    }

    /**
     * 
     */
    public function dashCallcenter($empresa =false)
    {
        $dados = [];

        //enviando para cobrança
        $query = "SELECT COUNT(*) AS total FROM telefonica_events where idEvent = 194 
        and DATE_FORMAT(adicionado,'%Y%m%d%') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d%')";
        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;

        }else{

            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_enviado_pagamento'] =  $r_total_pagamento[0]['total'];
        }


        //Contato com sucesso
        $query = "SELECT COUNT(*) AS total FROM telefonica_events where idEvent = 184 
        and DATE_FORMAT(adicionado,'%Y%m%d%') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d%')";
        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;
        }else{
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_contato_sucesso'] =  $r_total_pagamento[0]['total'];
        }



        //contato falhado
        $query = "SELECT COUNT(*) AS total FROM telefonica_events where idEvent = 183 
        and DATE_FORMAT(adicionado,'%Y%m%d%') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d%')";
        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;
        }else{
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_contato_falho'] =  $r_total_pagamento[0]['total'];
        }

        //pagamentos executados        
        $query = "SELECT COUNT(*) AS total FROM telefonica_events where idEvent = 152 
        and DATE_FORMAT(adicionado,'%Y%m%d%') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d%')";

        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;
        }else{
            
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_pagamentos_executados'] =  $r_total_pagamento[0]['total'];
        }

        //=============================================================

        $query = "SELECT COUNT(*) AS total FROM telefonica_events where idEvent = 153
         and DATE_FORMAT(adicionado,'%Y%m%d%') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d%')";

        if($empresa !== false)
        {
            $query.=" and empresa_atendimento = ?";
            $values['empresa_atendimento'] = $empresa;
        }else{
            
            $values = false;
        }
      
        if(($r_total_pagamento = $this->query_db($query,$values)) !== false)
        {
            $dados['total_cancelado'] =  $r_total_pagamento[0]['total'];
        }

        return $dados;

    }
}