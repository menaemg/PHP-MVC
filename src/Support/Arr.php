<?php

namespace MVC\Support;

use ArrayAccess;

class Arr
{
    public static function only(array $array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    public static function accessible($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    public static function exists($array, $key)
    {

        if (static::accessible($array)) {

            if ($array instanceof ArrayAccess) {
                return $array->offsetExists($key);
            }

            if (is_array($array)) {
                return array_key_exists($key, $array);
            }

        } 
        return false;
    }

    public static function has($array, $keys): bool
    {
        if ($keys) {
            $keys = (array) $keys;

            foreach ($keys as $key) {

                $subArray = $array;

                if (static::exists($array, $key)) {
                    continue;
                }

                foreach (explode('.', $key) as $segment) {
                    if (static::accessible($subArray) && static::exists($subArray, $segment)) {
                        $subArray = $subArray[$segment];
                    } else {
                        return false;
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
    
    public static function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            
            if (empty($array)) {
                return value($default);
            }
            
            return array_values($array)[0];

        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return value($default);
    }
    
    public static function last($array, callable $callback = null, $default = null)
    {

        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }
        
        return static::first(array_reverse($array, true), $callback, $default);
    }
    
    public static function forget(&$array, $keys)
    {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }

            if (str_contains($key, '.')){

                $parts = explode('.', $key);

                // clean up before each pass
                $array = &$original;

                while (count($parts) > 1) {

                    $part = array_shift($parts);

                    if (isset($array[$part]) && is_array($array[$part])) {
                        $array = &$array[$part];
                    } else {
                        continue 2;
                    }
                }

                unset($array[array_shift($parts)]);
            }
        }
    }

    public static function except(&$array , $keys)
    {
        static::forget($array, $keys);
    } 

    public static function flatten($array, $depth = INF)
    {
        $result = [];
        foreach($array as $item){

            if ($depth === 1){

                $result = array_merge($result, array_values( (array) $item));
                continue;
            } 

            if(is_array($item)) {

                $result = array_merge($result, static::flatten($item, $depth - 1));            
                continue;
            } 
            
            $result[] = $item;
            
        }
        return $result;
    }

    public static function get($array, $key, $default=null)
    {
        if (!static::accessible($array)) {
            return value($default);
        }

        if (is_null($key) ) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (!str_contains($key, '.')) {
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array, $segment) && static::exists($array , $segment)) {
                $array = $array[$segment];
                continue;
            }            
            return value($default);
        }

        return $array;
    }

    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array[] = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {

            $key = array_shift($keys);

            $array = &$array[$key];
        }

        return $array[array_shift($keys)] = $value;
    }

    public static function unset(&$array, $key) 
    {
        static::set($array, $key, null);
    }
}
