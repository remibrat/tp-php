<?php
namespace App;

use \App\Controllers\UserController;

class Router {

    private $params;
    private $routes;
    private $routeCalled;

    public function __construct()
    {
        $this->getRoutes();
        $this->manageUrl();
    }

    public function getRoutes()
    {
        include('routes.php');
        $uriParams = explode('?', $_SERVER['REQUEST_URI'], 2);
        $this->routeCalled = $uriParams[0];
        $this->params = $this->getParams($uriParams[1]);
        $this->routes =  getRoutes();
        return;
    }

    public function manageUrl()
    {
        if (!empty($this->routes[$this->routeCalled])) {
            $c =  $this->routes[$this->routeCalled]["controller"]."Controller";
            $a =  $this->routes[$this->routeCalled]["action"]."Action";
        
            
               try {
                   
                    $controller = new UserController();
               } catch( \Throwable $t) {
                    die("Le fichier controller n'existe pas");
               }
                //Vérifier que la class existe et si ce n'est pas le cas faites un die("La class controller n'existe pas")
                
                    
                    //Vérifier que la méthode existeet si ce n'est pas le cas faites un die("L'action' n'existe pas")
                    if (method_exists($controller, $a)) {
                        
                        //EXEMPLE :
                        //$controller est une instance de la class UserController
                        //$a = userAction est une méthode de la class UserController
                        $controller->$a($this->params);
                    } else {
                        die("L'action' n'existe pas");
                    }
                
        } else {
            die("L'url n'existe pas : Erreur 404");
        }
        
    }


function getParams($params) {
    $explodedParams = explode('&', $params, 2);
    $result = [];
    foreach($explodedParams as $param) {
        $data = explode("=", $param);
        $result[$data[0]] =  $data[1];
    }
    return $result;
}
}