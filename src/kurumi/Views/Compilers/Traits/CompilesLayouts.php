<?php


namespace Kurumi\Views\Compilers\Traits;


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
        $echo = "<?php \$__temp->make($expresion) ?>";
        $this->footer[] = $echo;

        return '';
    }



    /**
     *  
     *  compiles @zafkiel menjadi php yang valid.
     * 
     *  @return string 
     **/
    public function compilesZafkiel(): string 
    {
        return '<?php echo $__temp->zafkielContent($1) ?>';
    }



    /**
     *  
     *  compiles @kurumiSection menjadi php yang valid.
     * 
     *  @return string 
     **/
    public function compilesKurumiSection(): string 
    {
        return '<?php $__temp->startSection($1) ?>$2<?php $__temp->stopSection(); ?>';
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
        return '<?php $__temp->startSection($1) ?>';
    }
}
