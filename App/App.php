<?php

namespace App;

use App\Controllers\HomeController;
use Exception;

class App
{
    private $controller;
    private $controllerFile;
    private $action;
    private $params;
    public  $controllerName;

    public function __construct()
    {
        /*
         * Constantes do sistema
         */
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']))
        {
            $uri = 'https://';
            
        } else {

            $uri = 'http://';
        }

        define('APP_HOST'       , $_SERVER['HTTP_HOST'] . "/");
        define('APP_HOST_ALL'       , $uri.$_SERVER['HTTP_HOST'] . "/");
        define('COOKIE_PATH',    $_SERVER['HTTP_HOST']);
        define('PATH'           , realpath('./'));
        define('TITLE'          , "Cine-Auto");

        define('DIR_LOG'        ,'/home/cineauto/public_html/App/Log/log.txt');
        define('DIR_REST_LIB'        ,'/home/cineauto/public_html/vendor/mashape/unirest-php/src/Unirest.php');
      
         //configuração do banco de dados producao
         define('DB_HOST'        , "cineauto.com.br");
         define('DB_USER'        , "cineauto_cineauto");
         define('DB_NAME'        , "cineauto_drive");
         define('DB_PASSWORD'    , "+@piR%zR)Ifr");
         define('DB_DRIVER'      , "mysql");

         //configuração de imagems 

         define('IMG_LARGURA'        ,  1500);
         define('IMG_ALTURA'        ,   2000);
         define('IMG_TAMANHO'        , 2000000);

        define("INCLUDE_PATH" ,$_SERVER['REQUEST_SCHEME'].":/".APP_HOST."App/Views/public/");

        define('PATH_PUBLIC_HTML' , $uri.$_SERVER['HTTP_HOST']."/public/");

        define('PATH_PUBLIC_HTML_ADMIN' , $uri.$_SERVER['HTTP_HOST']."/App/Views/admin/public/");

        define('PATH_REPOSITORIO_IMG' , '/home/cineauto/public_html/App/Lib/Repositorio/fotos/');

        define('PATH_REPOSITORIO_IMG_LINK' , $uri.$_SERVER['HTTP_HOST'].'/App/Lib/Repositorio/fotos/');

        //echo PATH_PUBLIC_HTML_ADMIN;
      
        define("TOKEN",sha1(sha1('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])));
        set_time_limit(30);


        //configuração de cookie
       /* ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 1);
        header("X-Frame-Options: SAMEORIGIN");*/

        header('Content-Type: text/html; charset=iso-8859-1');

        $this->url();

    }

    public function run()
    {
        if ($this->controller) {
            $this->controllerName = ucwords($this->controller) . 'Controller';
            $this->controllerName = preg_replace('/[^a-zA-Z]/i', '', $this->controllerName);
           
        } else {
            
            $this->controllerName = "HomeController";

        }

     $this->controllerFile = $this->controllerName . '.php';

     
        $this->action  = preg_replace('/[^a-zA-Z]/i', '', $this->action);

     
       
        if (!$this->controller) {
           
            
            $this->controller = new HomeController($this);
            $this->controller->index();
           
        } 

        if (!file_exists(PATH . '/App/Controllers/' . $this->controllerFile)) {
            throw new Exception("PÃ¡gina nÃ£o encontrada.", 404);
        }

        $nomeClasse     = "\\App\\Controllers\\" . $this->controllerName;
        $objetoController = new $nomeClasse($this);

        if (!class_exists($nomeClasse)) {
            throw new Exception("Erro na aplicaÃ§Ã£o", 500);
        }
        
        if (method_exists($objetoController, $this->action)) {
            $objetoController->{$this->action}($this->params);
            return;
        } else if (!$this->action && method_exists($objetoController, 'index')) {
            $objetoController->index($this->params);
            return;
        } else {
            throw new Exception("Nosso suporte jÃ¡ esta verificando desculpe!", 500);
        }
        throw new Exception("PÃ¡gina nÃ£o encontrada.", 404);
    }

    public function url () {

        if ( isset( $_GET['url'] ) ) {

            $path = $_GET['url'];
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL); 

            $path = explode('/', $path);


            $this->controller  = $this->verificaArray( $path, 0 );
            $this->action      = $this->verificaArray( $path, 1 );

            if ( $this->verificaArray( $path, 2 ) ) {
                unset( $path[0] );
                unset( $path[1] );
                $this->params = array_values( $path );
            }
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    private function verificaArray ( $array, $key ) {

        if ( isset( $array[ $key ] ) && !empty( $array[ $key ] ) ) {
            return $array[ $key ];
        }
        return null;
    }
}