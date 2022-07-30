<?php

use Dotenv\Dotenv;
use MVC\Http\Request;
use MVC\Http\Response;
use MVC\Http\Route;

require_once __DIR__ . "/../src/Support/helpers.php";
require_once base_path() . "/vendor/autoload.php";
require_once base_path() . "/routes/web.php";

$env = Dotenv::createImmutable(base_path());

$env->load();

var_dump($_ENV);

$route = new Route(new Request, new Response);

$route->resolve();

// var_dump($route->Request->params());

// var_dump($action());
// dump(Route::$routes['get']['/']());



// dump(Route::$routes);