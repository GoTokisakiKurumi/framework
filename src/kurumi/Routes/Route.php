<?php

namespace Kurumi\Routes;

use Kurumi\Routes\RouteInterfaces;

/**
 *
 *  class untuk kebutuhan routing.
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
class Route implements RouteInterfaces {

    /**
     *
     *  @property array $handler 
     *
    **/
    private static array $handler = [];

    /**
     *
     *  @method route get() 
     *  @return void 
     * 
     **/
    public static function get(string $path, callable $handler): void
    {
        self::addHandler('GET', $path, $handler);
    }

    /**
     *
     *  @method route post() 
     *  @return void 
     * 
     **/
    public static function post(string $path, callable $handler): void
    {
        self::addHandler('POST', $path, $handler);
    }

    /**
     *
     *  @method route addHandler() 
     *  @return void 
     * 
     **/
    private static function addHandler(string $method, string $path, $handler): void
    {
        self::$handler[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler
        ];
    }

    /**
     *
     *  @method route run() 
     *
     *  untuk menjalankan route yang sudah ditentukan.
     *
     *  @return void 
     * 
     **/
    public static function run(): void
    {
        $requestUri = parse_url($_SERVER["REQUEST_URI"]);
        $requestPath = $requestUri["path"];
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        $callback = null;

        foreach(self::$handler as $handler)
        {
            if ($handler['path'] === $requestPath AND $requestMethod === $handler["method"])
            {
                $callback = $handler["handler"];
            }
        }

        if (!$callback) {
            header("HTTP/1.0 404 Not Found");
            return;
        }

        echo call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }
}
