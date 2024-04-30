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
     *
     **/ 
    public function render(string $path): void;

}
