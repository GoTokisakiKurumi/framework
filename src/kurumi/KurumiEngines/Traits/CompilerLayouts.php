<?php


namespace Kurumi\KurumiEngines\Traits;


/**
 *
 *  @author Lutfi Aulia Sidik 
 **/
trait CompilerLayouts {

    
    public function compileKurumiExtends()
    {
        $echo = '<?php $__temp->extendContent($1) ?>';
        $this->footer[] = $echo;

        return '';
    }


    public function compileKurumiSection()
    {
        return '<?php $__temp->startContent($1) ?>$2<?php $__temp->stopContent(); ?>';
    }


    public function compileKurumiSingleSection()
    {
        return '<?php $__temp->startContent($1) ?>';
    }

    
    public function compileKurumiInclude()
    {
        return '<?php $__temp->includeFile($1) ?>';
    }
}
