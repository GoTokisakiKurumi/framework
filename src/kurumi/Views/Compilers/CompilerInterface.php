<?php


namespace Kurumi\Views\Compilers;


/**
 *
 *  Interface compiler
 *
 *  @author Lutfi Aulia Sidik
 **/
interface CompilerInterface {


    /**
     *  
     *  Memulai compile file.
     *  
     *  @param string $path 
     *  @param string $optional 
     *  @return void
     **/ 
    public function compile(string $path, string $optional): void;


    /**
     * 
     *  Set directory output untuk menyimpan
     *  hasil compile.
     *
     *  @param string $path
     *  @return void|mixed 
     **/
    public function setDirectoryOutput(string $path);

}
