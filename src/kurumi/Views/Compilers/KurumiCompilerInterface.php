<?php


namespace Kurumi\Views\Compilers;


/**
 *
 *  interface KurumiCompilerInterface 
 *
 *  @author Lutfi Aulia Sidik
 **/
interface KurumiCompilerInterface {



    /**
     * 
     *  Set directory input dan output.
     *
     *  @param string $input
     *  @param string $output
     *  @return object 
     **/
    public function setDirectory(string $input, string $output): object;



    /**
     *
     *  Set content file dan kembalikan 
     *  object ini.
     *
     *  @param string $path
     *  @return KurumiCompilerInterface 
     **/
    public function files(string $path): KurumiCompiler;



    /**
     *
     *  @return void
     **/ 
    public function compile(): void;

}
