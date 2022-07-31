<?php

namespace MVC;

use MVC\Http\Route;
use MVC\Http\Request;
use MVC\Http\Response;

class App
{
    protected Route $route;
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = new Request;
        $this->response = new Response;
        $this->route = new Route($this->request, $this->response);
    }

    public function run()
    {
        $this->route->resolve();
    }

    public function __get($name)
    {           
        return property_exists($this, $name) ?? $this->name;
    }
}