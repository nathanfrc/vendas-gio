<?php

namespace App\Controllers;
//use AppModelsMiddleMercadoBitcoins;
//use AppModelsDAOPesquisaDAO;
//use AppModelsDAOVoitoDAO;

class AdminController extends Controller
{

    public function index()
    {

        $this->validarLoginController();
        //$this->renderAdmin('login/index','Login', true);
    }

    public function logout()
    {
        $cookie =  new  \App\Lib\System\Cookie;
        $cookie->logout();
        $this->renderAdmin('login/index','Login', true);
    }

    public function logar()
    {
        try{

            if($_SERVER['REQUEST_METHOD'] == 'POST')
            { 

                $validarDados = new \App\Controllers\Validacao\LoginValidador();
                $validarDados->validarCadastro();
    
                if($validarDados->getErros())
                {
                   self::setViewParam('error',$validarDados->getErros());
                   self::setViewParam('login',$_REQUEST);
                   $this->index();
                }


                $this->logarController(trim($_REQUEST['usuario']), trim($_REQUEST['senha']));


            }else if ($_SERVER['REQUEST_METHOD'] == 'GET'){

                $this->index();

            }else{

                self::setViewParam('error',['Erro ao fazer Login']);
                $this->index();

            }

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function painel()
    {
        try{

            $this-> validarLoginController();

            $this->renderAdmin('painel/index','Dashboard');

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
        
    }

    /**
     * função para exibir genero 
     */
/*
     public function genero()
     {
         try{

            $generoDAO = new \App\Models\DAO\GeneroDAO();

            if(($dadosGeneros= $generoDAO->listarGerenos()) !==false)
            {
                self::setViewParam('listaDeGeneros',$dadosGeneros);
            }

        }catch(Exception $e)
        {
           echo $e->getMessage();
        }

        $this->renderAdmin('painel/genero/index','Listagem Gênero', false,true);

     }*/

     /**
      * Mostra a view de cadastro de generos 
      */

   /* public function cadastroGenero()
    {
        try{

            $this->renderAdmin('painel/genero/create','Criar Gênero', false,true);

        }catch(Exception $e)
        {
           echo $e->getMessage();
        }
    }*/

    /**
     * cadastra novo genero
     */
/*
    public function cadastrarGenero()
    {
        try{

            $validarDados = new \App\Controllers\Validacao\GeneroValidador();
            $validarDados->validarGeneroCadastro();

            if($validarDados->getErros())
            {
                self::setViewParam('error',$validarDados->getErros());
                self::setViewParam('titulo',$_REQUEST['titulo']);
                $this->renderAdmin('painel/genero/create','Criar Gênero', false,true);
            }

            $generoDAO = new \App\Models\DAO\GeneroDAO();

            $form['titulo'] = $_REQUEST['titulo'];

         if(($retDao =$generoDAO->createGenero($form)) !== false)
         {
            self::setViewParam('sucesso','Gênero Cadastrado com sucesso !!!');

         }else{

            self::setViewParam('error',['Erro ao cadastrar Gênero!! tente novamente']);
         }

            $this->renderAdmin('painel/genero/create','Criar Gênero', false,true);

        }catch(Exception $e)
        {
           echo $e->getMessage();
        }
    }

*/
    
     /**
      * Mostra a view de update e cadastro
      */
/*
      public function updateGenero($id)
      {
          try{

            
            if(!is_numeric($id[0]) or empty($id[0]))
            {
                self::setViewParam('error',['Erro no parametro de atualizar']);

                $this->genero();
            }

             $filtro['id'] = $id[0];

             $generoDAO = new \App\Models\DAO\GeneroDAO();

            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {          
    
                if(($dadosGeneros= $generoDAO->listarGerenos($filtro)) !==false)
                {
                    self::setViewParam('genero',$dadosGeneros);
                }
    
                $this->renderAdmin('painel/genero/update','Atualizar Gênero', false,true);

            }else if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                $validarDados = new \App\Controllers\Validacao\GeneroValidador();
                $validarDados->validarGeneroCadastro();
    
                if($validarDados->getErros())
                {
                    self::setViewParam('error',$validarDados->getErros());
                    self::setViewParam('genero',$_REQUEST['titulo']);
                    $this->renderAdmin('painel/genero/update','Update Gênero', false,true);
                }


                $form['titulo'] = $_REQUEST['titulo'];
    

               if(($ret = $generoDAO->atualizarGenero($filtro['id'], $form)) === true )
               {
                  self::setViewParam('sucesso','Gênero Cadastrado com sucesso !!!');
                  $this->genero();

               }else{

                    self::setViewParam('error',['Erro ao atualizar genero']);

                    if(($dadosGeneros= $generoDAO->listarGerenos($filtro)) !==false)
                    {
                        self::setViewParam('genero',$dadosGeneros);
                    }

                    $this->renderAdmin('painel/genero/update','Update Gênero', false,true);
               }

            }else{

                self::setViewParam('error',['Erro no parametro de atualizar']);
                $this->genero();
            }
    
          }catch(Exception $e)
          {
             echo $e->getMessage();
          }
      }


      public function local()
      {
        try{

            $locaisDAO = new \App\Models\DAO\LocalDAO();

            if(($dadosLocais =  $locaisDAO->listar()) !== false)
            {
                self::setViewParam('listaDeLocais',$dadosLocais);
              //  var_dump($dadosLocais);
            }

        }catch(Exception $e)
        {
           echo $e->getMessage();
        }

        $this->renderAdmin('painel/local/index','Listagem Local', false,true);

      }*/

      /**
       * Função para adicioanar local 
       */

       /*
       public function cadastrarLocal()
       {
         try{

            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {  

                 $this->renderAdmin('painel/local/create','Cadastro de Locais', false,true);
                
            }else if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                $validarDados = new \App\Controllers\Validacao\LocalValidador();
                $validarDados->validarCadastro();
    
                if($validarDados->getErros())
                {
                    self::setViewParam('error',$validarDados->getErros());
                    self::setViewParam('local',$_REQUEST);
                    $this->renderAdmin('painel/local/create','Cadastro de Locais', false,true);
                }

                $localDAO = new \App\Models\DAO\LocalDAO();


             if(($retDao =$localDAO->create($_REQUEST)) !== false)
             {
                self::setViewParam('sucesso','Local Cadastrado com sucesso !!!');
    
             }else{
    
                self::setViewParam('error',['Erro ao cadastrar Local!! tente novamente']);
             }
    
                $this->renderAdmin('painel/local/create','Criar Local', false,true);

            }else{

                self::setViewParam('error',['Erro no parametro de atualizar']);
                $this->genero();

            }
    

            }catch(Exception $e)
            {
              echo $e->getMessage();
            }

       }*/

        /**
      * Mostra a view de update e cadastro
      */
/*
      public function updateLocal($id)
      {
          try{

            if(!is_numeric($id[0]) or empty($id[0]))
            {
                self::setViewParam('error',['Erro no parametro de atualizar']);

                $this->local();
            }

             $filtro['id'] = $id[0];

             $localDAO = new \App\Models\DAO\LocalDAO();

            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {          
    
                if(($dadosLocal= $localDAO->listar($filtro)) !==false)
                {
                    self::setViewParam('local',$dadosLocal);
                }
    
                $this->renderAdmin('painel/local/update','Atualizar Gênero', false,true);

            }else if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                $validarDados = new \App\Controllers\Validacao\LocalValidador();
                $validarDados->validarCadastro();
    
                if($validarDados->getErros())
                {
                    self::setViewParam('error',$validarDados->getErros());
                    self::setViewParam('local',$_REQUEST);
                    $this->renderAdmin('painel/local/update','Update Local', false,true);
                }


               if(($ret = $localDAO->atualizarGenero($filtro['id'], $_REQUEST)) === true )
               {
                  self::setViewParam('sucesso','Local Cadastrado com sucesso !!!');
                  $this->local();

               }else{

                    self::setViewParam('error',['Erro ao atualizar local']);

                    if(($dadosLocal= $localDAO->listar($filtro)) !==false)
                    {
                        self::setViewParam('local',$dadosLocal);
                    }

                    $this->renderAdmin('painel/genero/update','Update Gênero', false,true);
               }

            }else{

                self::setViewParam('error',['Erro no parametro de atualizar']);
                $this->local();
            }
    
          }catch(Exception $e)
          {
             echo $e->getMessage();
          }
      }
    
*/
      //sessão 
      /*

      public function sessao()
      {
        try{

            $SessaoDAO = new \App\Models\DAO\SessaoDAO();

            if(($dadosSessao =  $SessaoDAO->listar()) !== false)
            {
                self::setViewParam('listaDeSessoes',$dadosSessao);
              //  var_dump($dadosLocais);
            }

        }catch(Exception $e)
        {
           echo $e->getMessage();
        }

        $this->renderAdmin('painel/sessao/index','Listagem Sessões', false,true);

      }
*/
      /**
       * Função para adicioanar local 
       */
/*
       public function cadastrarSessao()
       {
         try{

            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {  

                 $this->renderAdmin('painel/sessao/create','Cadastro de Sessões', false,true);
                
            }else if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                $validarDados = new \App\Controllers\Validacao\SessaoValidador();
                $validarDados->validarCadastro();
    
                if($validarDados->getErros())
                {
                    self::setViewParam('error',$validarDados->getErros());
                    self::setViewParam('sessao',$_REQUEST);
                    $this->renderAdmin('painel/sessao/create','Cadastro de Sessoes', false,true);
                }

                $locaSessao = new \App\Models\DAO\SessaoDAO();


             if(($retDao =$locaSessao->create($_REQUEST)) !== false)
             {
                self::setViewParam('sucesso','Sessão Cadastrado com sucesso !!!');
    
             }else{
    
                self::setViewParam('error',['Erro ao cadastrar Sessao!! tente novamente']);
             }
    
                $this->renderAdmin('painel/local/create','Criar Sessao', false,true);

            }else{

                self::setViewParam('error',['Erro no parametro de atualizar']);
                $this->genero();

            }
    

            }catch(Exception $e)
            {
              echo $e->getMessage();
            }

       }*/

        /**
      * Mostra a view de update e cadastro
      */

      /*
      public function updateSessao($id)
      {
          try{

            if(!is_numeric($id[0]) or empty($id[0]))
            {
                self::setViewParam('error',['Erro no parametro de atualizar']);

                $this->local();
            }

             $filtro['id'] = $id[0];

             $localDAO = new \App\Models\DAO\LocalDAO();

            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {          
    
                if(($dadosLocal= $localDAO->listar($filtro)) !==false)
                {
                    self::setViewParam('local',$dadosLocal);
                }
    
                $this->renderAdmin('painel/local/update','Atualizar Gênero', false,true);

            }else if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                $validarDados = new \App\Controllers\Validacao\LocalValidador();
                $validarDados->validarCadastro();
    
                if($validarDados->getErros())
                {
                    self::setViewParam('error',$validarDados->getErros());
                    self::setViewParam('local',$_REQUEST);
                    $this->renderAdmin('painel/local/update','Update Local', false,true);
                }


               if(($ret = $localDAO->atualizarGenero($filtro['id'], $_REQUEST)) === true )
               {
                  self::setViewParam('sucesso','Local Cadastrado com sucesso !!!');
                  $this->local();

               }else{

                    self::setViewParam('error',['Erro ao atualizar local']);

                    if(($dadosLocal= $localDAO->listar($filtro)) !==false)
                    {
                        self::setViewParam('local',$dadosLocal);
                    }

                    $this->renderAdmin('painel/genero/update','Update Gênero', false,true);
               }

            }else{

                self::setViewParam('error',['Erro no parametro de atualizar']);
                $this->local();
            }
    
          }catch(Exception $e)
          {
             echo $e->getMessage();
          }
      }*/
	
}