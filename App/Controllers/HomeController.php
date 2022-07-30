<?php

namespace App\Controllers;

use MVC\View\View;

class HomeController
{
    public function index()
    {
        return view('home');
    }
}