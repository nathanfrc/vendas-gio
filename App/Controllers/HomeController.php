<?php

namespace App\Controllers;
//use AppModelsMiddleMercadoBitcoins;
//use AppModelsDAOPesquisaDAO;
//use AppModelsDAOVoitoDAO;

class HomeController extends Controller
{
	
    public function index()
    {
        $filmesDAO = new \App\Models\DAO\FilmesDAO();
          
        if(($dadosFilmes = $filmesDAO->listarFilmes($filtro)) !== false)
        {
            self::setViewParam('listaDeFilmes',$dadosFilmes);  
        }

        $parceiroDAO = new \App\Models\DAO\ParceiroDAO();

         $filtros['ativo'] ='1';
      
        if(($dados= $parceiroDAO->listar($filtros)) !==false)
        {
            self::setViewParam('listaDeParceiros',$dados);
        }
        
        $this->render('home/index','Cinema drive-in em São paulo');
    
      //  header('Location: https://pixelticket.com.br/agencia/cine-auto-drive-in');
      exit;

    }




}