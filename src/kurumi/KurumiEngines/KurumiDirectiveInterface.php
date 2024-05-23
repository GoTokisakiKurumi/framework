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
     *  @param string $path
     *  @return void
     **/ 
    public function render(string $path): void;

}
