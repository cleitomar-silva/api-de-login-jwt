<?php

class Route
{
    private static $rotas = [];

    private $ultimoIndex;

    private static function add($metodoHttp, $uri, $action)
    {
        self::$rotas[] = [
            'metodoHttp' => strtoupper($metodoHttp),
            'uri'        => trim($uri, '/'),
            'controller' => $action[0],
            'metodo'     => $action[1],
            'middleware' => [],
        ];

        $obj = new static;
        $obj->ultimoIndex = array_key_last(self::$rotas);
        return $obj;
    }

    public static function get($uri, $action)
    {
        return self::add('GET', $uri, $action);
    }

    public static function post($uri, $action)
    {
        return self::add('POST', $uri, $action);
    }

    public static function put($uri, $action)
    {
        return self::add('PUT', $uri, $action);
    }

    public static function delete($uri, $action)
    {
        return self::add('DELETE', $uri, $action);
    }

    public function middleware($middlewares)
    {
        self::$rotas[$this->ultimoIndex]['middleware'] = (array) $middlewares;
    }

    public static function all()
    {
        return self::$rotas;
    }
}
