<?php


namespace Kurumi\KurumiEngines;


/**
 *
 *  interface KurumiDirectiveInterface 
 *
 *  @author Lutfi Aulia Sidik
 **/
interface KurumiDirectiveInterface {



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
     *  @return KurumiDirectiveInterface 
     **/
    public function files(string $path): KurumiDirectiveInterface;



    /**
     *
     *  @return void
     **/ 
    public function compile(): void;

}
