<?php


namespace Kurumi\Routes;


use Closure;
use Kurumi\Routes\RouteInterfaces;
use Whoops\Exception\ErrorException;


/**
 *
 *  class yang bertanggung jawab atas 
 *  semua routing.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class Route implements RouteInterfaces {


    /**
     *
     *  @property array $handler 
     **/
    private static array $handler = [];


    /**
     * 
     *  @property Closure|array $callback
     **/
    private static Closure|array $callback;



    /**
     *  
     *  GET
     *  
     *  @param string $path
     *  @param callable|array $handler
     *  @return void 
     **/
    public static function get(string $path, callable|array $handler): void
    {
        self::addHandler('GET', $path, $handler);
    }



    /**
     *  
     *  POST 
     *  
     *  @param string $path
     *  @param callable|array $handler 
     *  @return void 
     **/
    public static function post(string $path, callable|array $handler): void
    {
        self::addHandler('POST', $path, $handler);
    }



    /**
     *  
     *  Menambahkan route handler.
     *  
     *  @param string $method
     *  @param string $path 
     *  @param string $handler
     *  @return void 
     **/
    protected static function addHandler(string $method, string $path, $handler): void
    {
        self::$handler[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler
        ];
    }


    
    /**
     *
     *  Untuk menjalankan route yang sudah ditentukan.
     *
     *  @return void 
     **/
    public static function run(): void
    {
        $container  = app();
        $requestUri = parse_url($_SERVER["REQUEST_URI"]);
        $requestPath = $requestUri["path"];
        $requestMethod = $_SERVER["REQUEST_METHOD"];


        foreach(self::$handler as $handler) {
            if ($handler['path'] === $requestPath AND $requestMethod === $handler["method"]) {
                self::$callback = $handler["handler"];
            }
        }


        if (!isset(self::$callback)) {
            header("HTTP/1.0 404 Not Found");
            return;
        }


        if (is_array(self::$callback)) {

            [$namespace, $methodController] = self::$callback;
            
            $container->bind($namespace);
            $controller = $container->make($namespace);

            if (!method_exists($controller, $methodController)) {
                throw new ErrorException("Ara Ara sepertinya method ini $namespace::$methodController tidak tersedia.");
            }

            self::$callback = [
                $controller,
                $methodController
            ];
        }

        echo call_user_func_array(self::$callback, [
            array_merge($_GET, $_POST)
        ]);
    }
}
