<?php


namespace Kurumi\Views;

use Kurumi\FileSystems\FileSystem;
use Kurumi\Views\Compilers\CompilerInterface;
use Kurumi\Views\Compilers\StyleCompiler;
use Whoops\Exception\ErrorException;


/**
 *  
 *  Factory
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
     *
     *  @property-read Kurumi\Views\Compilers\KurumiCompiler $kurumiCompiler
     *  @property-read Kurumi\Views\Compilers\StyleCompiler  $styleCompiler
     *  @property-read Kurumi\FileSystems\FileSystem         $files
     **/
    public function __construct(
        protected readonly CompilerInterface $kurumiCompiler,
        protected readonly StyleCompiler $styleCompiler,
        protected readonly FileSystem $files
    ){}



    /**
     *
     *  @param string $view
     *  @param string $data
     *  @return Kurumi\Views\View
     **/
    public function make(string $view, array $data = [])
    {
        if ($view) $this->setView($view);
 
        $this->kurumiCompiler();

        $data = array_merge(["__temp" => $this], $data);

        return $this->viewInstance($this->getPathStorage(), $data);
    }



    /**
     *
     *  Import file memungkinkan untuk memisahkan 
     *  file (css, js) dan secara otomatis akan dicompile 
     *  menjadi satu file.
     *
     *  @param string      $path
     *  @param string|null $key 
     *  @throws ErrorException jika file tidak ditemukan.
     *  @return void 
     **/
    public function import(string $path, string $key = null): void
    {
        $path = dirname($this->getPathViews()) . '/' . $path;

        if (!$this->files->exists($path)) {
            throw new ErrorException("File tidak ditemukan: $path");
        }

        $this->styleCompiler()->compile($path, $key);
    }



    /**
     *
     *  Terjemahkan directive menjadi php 
     *  yang valid.
     *  
     *  @return void 
     **/
    protected function kurumiCompiler(): void
    {
        $compiler = $this->kurumiCompiler;
        $compiler->setPathInput($this->getPathViews());
        $compiler->compile(path: $this->getPathStorage());
    }



    /**
     * 
     *  Gabungkan dari beberapa file menjadi satu 
     *  file.
     *
     *  @return Kurumi\Views\Compilers\StyleCompiler
     **/
    protected function styleCompiler(): StyleCompiler
    {
        $compiler = $this->styleCompiler;
        $compiler->setDirectoyOutput($this->getDirectoryPublic());
        return $compiler;
    }



    /**
     *  
     *  Instance object view. 
     *  
     *  @param string $view 
     *  @param array $data
     *  @return Kurumi\Views\View 
     **/
    public function viewInstance(string $path, array $data = []): View
    {
        return new View($this->files, $path, $data);
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
     *  Dapatkan directory lengkap ke public.
     *
     *  @return string  
     **/
    protected function getDirectoryPublic(): string
    {
        $path = app()->get("path.public");
        return $path;
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
