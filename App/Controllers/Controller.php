<?php

namespace App\Controllers;

use App\Lib\Sessao;

abstract class Controller
{
    protected $app;
    private $viewVar;

    public function __construct($app)
    {
        $this->setViewParam('nameController',$app->getControllerName());
        $this->setViewParam('nameAction',$app->getAction());
    }

    public function render($view, $titulo)
    {
        $viewVar   = $this->getViewVar();
       // $Sessao    = Sessao::class;

        require_once PATH . '/App/Views/__layouts/__head.php';
        require_once PATH . '/App/Views/__layouts/__menu.php';
        require_once PATH . '/App/Views/' . $view . '.php';
        require_once PATH . '/App/Views/__layouts/__footer.php';
        exit;
    }

    /**
     * View arquivo view , $titulo , view login sem menu
     */
    public function renderAdmin($view,$titulo,$login = false, $tinker = false)
    {
        $viewVar   = $this->getViewVar();
       // $Sessao    = Sessao::class;
        require_once PATH . '/App/Views/admin/__layouts_admin/__head_admin.php';
        if($login === false){  require_once PATH . '/App/Views/admin/__layouts_admin/__menu_admin.php';}
        require_once PATH . '/App/Views/admin/' . $view . '.php';
        if($tinker === true){  require_once PATH . '/App/Views/admin/__layouts_admin/__tinytable_admin.php';}
        require_once PATH . '/App/Views/admin/__layouts_admin/__footer_admin.php';
        exit;
    }


	
	public function renderConstruct($view, $titulo)
    {
        $viewVar   = $this->getViewVar();
       // $Sessao    = Sessao::class;

        #require_once PATH . '/App/Views/__layouts/__head.php';
        #require_once PATH . '/App/Views/__layouts/__menu.php';
        require_once PATH . '/App/Views/' . $view . '.php';
        #require_once PATH . '/App/Views/__layouts/__footer.php';

    }
	
    public function renderDinamico($view, $dash)
    {
        $viewVar   = $this->getViewVar();
       // $Sessao    = Sessao::class;

        require_once PATH . '/App/Views/__layouts/__header.php';
        require_once PATH . '/App/Views/__layouts/__nav.php';
        require_once PATH . '/App/Views/__layouts/__dashboard'.$dash.'.php';
        require_once PATH . '/App/Views/__layouts/__inicio_body.php';
        require_once PATH . '/App/Views/' . $view . '.php';
        require_once PATH . '/App/Views/__layouts/__fim_body.php';
        require_once PATH . '/App/Views/__layouts/__footer.php';

    }

    public function renderRoubo($view, $dash = false)
    {
        $viewVar   = $this->getViewVar();
       // $Sessao    = Sessao::class;

        require_once PATH . '/App/Views/__layouts/__header.php';
        require_once PATH . '/App/Views/__layouts/__nav.php';
        if($dash === true){  require_once PATH . '/App/Views/__layouts/__dashboardFurto.php';}
        require_once PATH . '/App/Views/__layouts/__inicio_body.php';
        require_once PATH . '/App/Views/' . $view . '.php';
        require_once PATH . '/App/Views/__layouts/__fim_body.php';
        require_once PATH . '/App/Views/__layouts/__footer.php';

    }

    public function renderOrcamento($view, $dash = false)
    {
        $viewVar   = $this->getViewVar();
       // $Sessao    = Sessao::class;

        require_once PATH . '/App/Views/__layouts/__header.php';
        require_once PATH . '/App/Views/__layouts/__nav.php';
        if($dash === true){  require_once PATH . '/App/Views/__layouts/__dashboardOrcamento.php';}
        require_once PATH . '/App/Views/__layouts/__inicio_body.php';
        require_once PATH . '/App/Views/' . $view . '.php';
        require_once PATH . '/App/Views/__layouts/__fim_body.php';
        require_once PATH . '/App/Views/__layouts/__footer.php';

    }

    public function redirect($view)
    {
        header('Location:' . APP_HOST_ALL . $view);
        exit;
    }

    public function redirectApi($view)
    {
        header('Location: http://sistemadilainox.pe.hu' . $view);
        exit;
    }

    public function getViewVar()
    {
        return $this->viewVar;
    }

    public function setViewParam($varName, $varValue)
    {
        if ($varName != "" && $varValue != "") {
            $this->viewVar[$varName] = $varValue;
        }
    }


    
    public function renderLogin($view)
    {
        $viewVar   = $this->getViewVar();

    
       // $Sessao    = Sessao::class;

      // echo '/App/Views/' . $view . '.php';

       // require_once PATH . '/App/Views/admin/layouts/__header.php';
        //require_once PATH . '/App/Views/admin/layouts/__nav.php';
        require_once PATH . '/App/Views/' . $view . '.php';
       // require_once PATH . '/App/Views/layouts/footer.php';
    }


    public function renderPublic($view)
    {
        $viewVar   = $this->getViewVar();
    
        require_once PATH . '/App/Views/public/' . $view . '.php';
       
    }


    public function logarController($user,$senha)
    {
        try{


            $login = new \App\Lib\System\Login;

           if(($retorno = $login->logarSystem($user,$senha)) === false)
           {
             self::setViewParam('error',['Senha e/ou login inválido(s)']);
              $this->renderAdmin('login/index','Login', true);
           }

           $this->renderAdmin('painel/index','Dashboard');

        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function validarLoginController()
    {
        try{

            $login = new \App\Lib\System\Login;

            if(($retorno = $login->validarLoginSystem()) === false)
            {
                $this->renderAdmin('login/index','Login', true);
            }
 
            $this->renderAdmin('painel/index','Dashboard');


        }catch(Exceptin $e)
        {
            echo $e->getMessage();
        }

    }

    public function validarLoginControllerIndex()
    {
        try{

            $login = new \App\Lib\System\Login;

            if(($retorno = $login->validarLoginSystem()) === false)
            {
                $this->renderAdmin('login/index','Login', true);
            }

 
           return true;


        }catch(Exceptin $e)
        {
            echo $e->getMessage();
        }

    }


    public function trataURL()
    {
        if(isset($_REQUEST['url']))
        {
           $array =  explode("/",$_REQUEST['url']);
           return $array[1];
        }
        
    }


}