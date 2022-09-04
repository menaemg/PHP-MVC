<?php

namespace MVC\Http;

class Request
{
    public $path = '/';

    public function method()
    {
        return  strtolower( $_SERVER['REQUEST_METHOD'] ) ;
    }

    public function path()
    {
        $path = $_SERVER["REQUEST_URI"] ?? '/';

        return str_contains($path, '?') ? explode('?' , $path)[0] : $path;
    }

    public function params()
    {
        // $path = $_SERVER["REQUEST_URI"];
        // $path = str_contains($path, '/') ? explode('/' , $path ,3)[1] : '';
        // if (str_contains($path ,"?" )){
        //     $ex = explode('?', $path );
        //     array_shift($ex);
        //     foreach($ex as $item){
        //         if(str_contains($item ,"=" )){
        //             $explode = explode('=', $item , 2);
        //             $params[] = [$explode[0]  => $explode[1]];
        //         } else {
        //             $params[] = $item;
        //         }
        //     }
        //     return $params;
        // } else {
        //     return [];
        // }
        
        $path = $this->path();
        $params = str_replace($path, '', $_SERVER["REQUEST_URI"] ) ;
        $params = trim( $params , '?') ;
        $params = explode('?' , $params);

        $newParams = [];
        foreach($params as $param){
            
            if (str_contains($param,'=')){

                $param = trim( $param , '=');
                $param = explode('=', $param, 2);
                $newParams[] = [$param[0] => $param[1]];
                
            } else {
                $newParams[] = null; 
            }
        }
        return $newParams; 
    }
}