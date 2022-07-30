<?php

namespace MVC\Http;

use MVC\View\View;

class Route
{
    public Request $Request;
    protected Response $Response;
    public static array $routes;

    public function __construct(Request $Request,Response $Response)
    {
        $this->Request  = $Request;
        $this->Response = $Response;
    }

    public static function get($route, callable|array|string $action)
    {
        self::$routes['get'][$route] = $action;
    }

    public static function post($route, callable|array|string $action)
    {
        self::$routes['post'][$route] = $action;
    }

    public function resolve()
    {
        $path = $this->Request->path();
        $method = $this->Request->method();
        $params = $this->Request->params();
        $action = self::$routes[$method][$path] ?? false;

        if (!array_key_exists($path , self::$routes[$method])){
            View::makeError('404');
        }

        // return $action;

        //404 Handling

        if(is_callable($action)){
            call_user_func($action, ...$params);
        }

        if(is_array($action)){
            call_user_func_array([new $action[0], $action[1]] , $params);
        }

        if(is_string($action)){
            $action = explode('@' , $action);
            $controller = "App\Controllers\\" . $action[0];
            call_user_func_array([new $controller, $action[1]] , $params);
        }

    }

}