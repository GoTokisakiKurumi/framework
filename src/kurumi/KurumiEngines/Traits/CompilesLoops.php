<?php


namespace Kurumi\KurumiEngines\Traits;


/**
 *
 *  Trait yang bertanggung jawab atas 
 *  compiles loops.
 *
 *  @author Lutfi Aulia Sidik 
 **/
trait compilesLoops {
    

    /**
     *
     *  compiles @kurumiforeach menjadi php yang valid.
     *  
     *  @return string 
     **/   
    protected function compilesForeach(): string
    {
        return '<?php foreach($1): ?>$2<?php endforeach; ?>';
    }
}
