<?php

use MVC\Http\Request;
use MVC\Http\Response;
use MVC\Http\Route;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../routes/web.php";


$route = new Route(new Request, new Response);

$route->resolve();

// var_dump($route->Request->params());

// var_dump($action());
// dump(Route::$routes['get']['/']());



// dump(Route::$routes);