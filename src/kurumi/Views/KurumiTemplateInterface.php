<?php


namespace Kurumi\Views;


use Kurumi\Views\View;


/**
 *
 *  interface KurumiTemplateInterface 
 *
 *  @author Lutfi Aulia Sidik
 **/
interface KurumiTemplateInterface {



    /**
     * 
     *  set instance object View.
     *
     *  @param View $view
     *  @return void 
     **/
    public function setView(View $view): void;



    /**
     *
     *  @param string $path
     *  @return void
     **/ 
    public function render(string $path, array $data = []): void;

}
