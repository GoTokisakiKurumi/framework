<?php

namespace Kurumi\KurumiTemplates;

/**
 *
 *  interface KurumiTransformInterface 
 *
 *  @author Lutfi Aulia Sidik
 *
 **/
interface KurumiTransformInterface {

    /**
     *
     *  @method render()
     *  @param string $path
     *  @return void
     **/ 
    public function render(string $path): void;

}
