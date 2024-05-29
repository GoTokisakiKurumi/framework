<?php


/**
 *
 *  Helpers.php yang bertanggung jawab atas
 *  semua function Helper/utility.
 *
 *  @author Lutfi Aulia Sidik
 **/


use Kurumi\Container\Container;
use Kurumi\Views\Factory;


define('PATH_PUBLIC', '../public/');
define('PATH_RESOURCES', '../resources/');
define('PATH_VIEWS', '../resources/views/');
define('PATH_STORAGE', '../storage/');
define('PATH_STORAGE_APP', '../storage/app/');
define('PATH_STORAGE_PUBLIC', '../storage/app/public/');



if (!function_exists('app'))
{

    /**
     *
     *  Dapatkan instance object dari container.
     *  
     *  @param string|null  $abstract
     *  @return object  
     **/
    function app(?string $abstract = null): object
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract);
    }
}



if (!function_exists('pathToDot'))
{

    /**
     *  
     *  Ubah path / string ke . 
     *  Example:
     *      path/folder/you.php => path.folder.you.php
     *  
     *  @param string $path 
     *  @param bool $reverse 
     *  @return void 
     **/
    function pathToDot(string $path, bool $reverse = false): string
    {
        return ($reverse) ? 
            str_replace('.', '/', $path) :
            str_replace('/', '.', $path);
    }
}



if (!function_exists('isFileUpdate'))
{

    /**
     *
     *  Cek apakah sebuah file ada perubahan
     *  berdasarkan waktu terakhir diupdate. 
     *
     *  @param string $input 
     *  @param array  $output 
     *  @return bool
     **/
    function isFileUpdate(string $input, string $output): bool
    {
        return app('filesystem')->isFileUpdate(
            input: $input,
            output: $output
        );
    } 
}



if (!function_exists('oppai'))
{
    function oppai(...$args)
    { 
        foreach ($args as $x) {
            (new Kurumi\Debug\CustomDumper)->dump($x);
        }

        die(1);
    }
}



if (!function_exists('view'))
{
 
    /**
     *
     *  Tampilkan kontent untuk ditampilkan.
     *
     *  @param string $view 
     *  @param array  $data 
     *  @return void 
     **/
    function view(string $view, array $data = [])
    {
        $factory = app(Factory::class);
        $factory->make($view, $data)->render();
    } 
}
