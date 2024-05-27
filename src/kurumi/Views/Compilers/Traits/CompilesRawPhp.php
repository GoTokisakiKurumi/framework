<?php


namespace Kurumi\Views\Compilers\Traits;


/**
 *
 *  Trait yang bertanggung jawab atas 
 *  compiles php mentah.
 *
 *  @author Lutfi Aulia Sidik 
 **/
trait compilesRawPhp {
    

    /**
     *
     *  compiles @kurumiphp menjadi php yang valid.
     *  
     *  @return string 
     **/   
    protected function compilesKurumiPhp(): string
    {
        return '<?php $1 ?>';
    }
}
