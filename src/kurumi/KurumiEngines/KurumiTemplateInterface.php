<?php


namespace Kurumi\KurumiEngines;


/**
 *
 *  interface KurumiTemplateInterface 
 *
 *  @author Lutfi Aulia Sidik
 **/
interface KurumiTemplateInterface {



    /**
     *
     *  @param string $path
     *  @return void
     **/ 
    public function render(string $path, array $data = []): void;

}
