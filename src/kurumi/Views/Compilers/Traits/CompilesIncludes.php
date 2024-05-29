<?php


namespace Kurumi\Views\Compilers\Traits;


/**
 *
 *  Trait yang bertanggung jawab atas 
 *  compiles includes.
 *
 *  @author Lutfi Aulia Sidik 
 **/
trait compilesIncludes {


    /**
     *
     *  compiles @kurumiInclude menjadi php yang valid.
     *  
     *  @return string 
     **/   
    public function compilesKurumiInclude(): string
    {
        return '<?php $__temp->make($1)->render() ?>';
    }



    /**
     *
     *  compiles @kurumiImport menjadi php yang valid.
     *  
     *  @return string 
     **/   
    public function compilesKurumiImport(): string
    {
        return '<?php $__temp->import($1) ?>';
    }

}
