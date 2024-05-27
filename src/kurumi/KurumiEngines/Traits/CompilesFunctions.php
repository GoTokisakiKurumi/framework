<?php


namespace Kurumi\KurumiEngines\Traits;


/**
 *
 *  Trait yang bertanggung jawab atas 
 *  compiles function.
 *
 *  @author Lutfi Aulia Sidik 
 **/
trait compilesFunctions {


    /**
     *
     *  compiles @oppai menjadi php yang valid.
     *
     **/ 
    protected function compilesOppai(): string
    {
        return '<?php oppai($1) ?>';
    }
}

