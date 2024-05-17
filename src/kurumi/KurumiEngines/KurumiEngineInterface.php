<?php


namespace Kurumi\KurumiEngines;


/**
 *
 *  interface KurumiEngineInterface 
 *
 *  @author Lutfi Aulia Sidik
 **/
interface KurumiEngineInterface {



    /**
     *
     *  @param string $path
     *  @return void
     **/ 
    public function render(string $path): void;

}
