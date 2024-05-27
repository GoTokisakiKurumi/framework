<?php


namespace Kurumi\KurumiEngines\Traits;


/**
 *
 *  Trait yang bertanggung jawab atas 
 *  compiles Layouts.
 *
 *  @author Lutfi Aulia Sidik 
 **/
trait compilesLayouts {


    /**
     *
     *  compiles @kurumiExtends menjadi php yang valid 
     *
     *  @param string $expresion 
     *  @return string 
     **/
    public function compilesKurumiExtends(string $expresion): string
    {
        $echo = "<?php \$__temp->extendContent($expresion) ?>";
        $this->footer[] = $echo;

        return '';
    }



    /**
     *  
     *  compiles @kurumiContent menjadi php yang valid.
     * 
     *  @return string 
     **/
    public function compilesKurumiContent(): string 
    {
        return '<?php echo $__temp->content($1) ?>';
    }



    /**
     *  
     *  compiles @kurumiSection menjadi php yang valid.
     * 
     *  @return string 
     **/
    public function compilesKurumiSection(): string 
    {
        return '<?php $__temp->startContent($1) ?>$2<?php $__temp->stopContent(); ?>';
    }



    /**
     *
     *  compiles @kurumiSection single menjadi 
     *  php yang valid.
     *  
     *  @return string 
     **/
    public function compilesKurumiSingleSection(): string
    {
        return '<?php $__temp->startContent($1) ?>';
    }
}
