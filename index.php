<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

function myAutoloader($class)
{
        $class = str_replace('App', '', $class);
      
        $class = str_replace('\\', '/', $class);
        if($class[0] == '/') {
            include  substr($class.'.php', 1);
        } else {           
            include $class.'.php';
        }
        
    
}

spl_autoload_register("myAutoloader");


use App\Core\ConstantLoader;
use App\Router;

new ConstantLoader();
new Router();
