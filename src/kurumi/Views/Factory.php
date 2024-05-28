<?php


namespace Kurumi\Views;

use Kurumi\Views\Compilers\CompilerInterface;


/**
 *
 *
 *  @author Lutfi Aulia Sidik 
 **/
final class Factory {


    use Traits\ManagesLayouts;


    /**
     * 
     *  Menyimpan nama view.
     *  
     *  @property string $view
     **/
    protected string $view;



    /**
     * 
     *  @property-read Kurumi\Views\View $viewInstance
     *  @property-read Kurumi\Views\Compilers\KurumiCompiler $compiler
     **/
    public function __construct
    (
        protected readonly View $viewInstance,
        protected readonly CompilerInterface $compiler
    ){}



    /**
     *
     * 
     *
     **/
    public function make(string $view, array $data = [])
    {
        if ($view) $this->setView($view);
 
        $this->compiler();

        $data = array_merge([
            "__temp" => $this,
            "__view" => $view
        ], $data);

        $this->viewInstance->render($this->getPathStorage(), $data);
    }



    /**
     *
     *  Terjemahkan directive menjadi php 
     *  yang valid.
     *  
     *  @return void 
     **/
    protected function compiler(): void
    {
        $compiler = $this->compiler;
        $compiler->setPathInput($this->getPathViews());
        $compiler->compile(path: $this->getPathStorage());
    }



    /**
     * 
     *  Set nama view.
     *
     *  @param string $view 
     *  @return void 
     **/
    protected function setView($view): void
    {
        $this->view = $view;
    }



    /**
     *  
     *  Dapatkan path lengkap ke 
     *  resources/views.
     *
     *  @return string 
     **/
    protected function getPathViews(): string
    {
        $path = app()->get("path.resources") 
            . "/views/" 
            . $this->view
            . ".kurumi.php";

        return $path;
    }



    /**
     *
     *  Dapatkan path lengkap ke 
     *  storage/app/public.
     *
     *  @return string 
     **/
    protected function getPathStorage(): string 
    {
        $path = app()->get("path.storage") 
            . "/app/public/" 
            . pathToDot($this->view)
            . ".php";

        return $path;
    }
}
