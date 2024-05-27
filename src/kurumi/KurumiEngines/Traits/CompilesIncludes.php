<?php


namespace Kurumi\KurumiEngines\Traits;


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
        return '<?php $__temp->includeFile($1) ?>';
    }



    /**
     *
     *  compiles @kurumiImport menjadi php yang valid.
     *  
     *  @return string 
     **/   
    public function compilesKurumiImport(): string
    {
        return '<?php $__temp->importFile($__view, $1) ?>';
    }

}
