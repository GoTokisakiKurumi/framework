<?php

namespace Kurumi\Routes;

/**
 *
 *  interface untuk kebutuhan class Route.
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
interface RouteInterfaces 
{
    /**
     * 
     *  @method get()
     *
     *  @param string $path
     *  @param callable $handler
     *  @return void 
     *  
     **/
    public static function get(string $path, callable $handler): void;

    /**
     * 
     *  @method post()
     *
     *  @param string $path
     *  @param callable $handler
     *  @return void 
     *  
     **/
    public static function post(string $path, callable $handler): void;

    /**
     * 
     *  @method run()
     *
     *  @return void 
     *
     **/
    public static function run(): void;
}
