<?php


namespace Kurumi\Views\Compilers;


/**
 *
 *  interface CompilerInterface 
 *
 *  @author Lutfi Aulia Sidik
 **/
interface CompilerInterface {


    /**
     *  
     *  Memulai compile file.
     *  
     *  @param string $path 
     *  @return void
     **/ 
    public function compile(string $path, string $optional): void;


    /**
     * 
     *  Set path input.
     *
     *  @param string $path
     *  @return void|mixed 
     **/
    public function setDirectoryOutput(string $path);

}
