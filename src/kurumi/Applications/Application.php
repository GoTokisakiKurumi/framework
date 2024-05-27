<?php


namespace Kurumi\Applications;


use Kurumi\Views\View;
use Kurumi\Consoles\Command;
use Kurumi\Container\ContainerInterface;
use Kurumi\FileSystems\FileSystem;
use Kurumi\Views\KurumiTemplate;
use Kurumi\Views\KurumiTemplateInterface;
use Kurumi\Views\Compilers\KurumiCompiler;
use Kurumi\Views\Compilers\KurumiCompilerInterface;


/**
 *
 *  Class yang bertanggung jawab atas
 *  configurasi awal framework.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class Application {


    
    public function __construct(
        public string $basePath,
        protected ContainerInterface $container
    )
    {
        $this->registerPageErrorHandler();
        $this->sharedPathsInContainer();
        $this->registerClassBindings();
        $this->registerClassSupports();
        $this->registerHelperFunction();
    }



    /**
     *  
     *  Register class ke container.
     *
     *  @return void 
     **/
    protected function registerClassBindings(): void
    {
        $this->container->bind(View::class);
        $this->container->bind(KurumiTemplateInterface::class, KurumiTemplate::class);
        $this->container->bind(KurumiCompilerInterface::class, KurumiCompiler::class);
    }



    /**
     * 
     *  Register class pendukung.
     *
     *  @return void 
     **/
    protected function registerClassSupports(): void
    {
        $this->container->bind('command', Command::class);
        $this->container->bind('filesystem', FileSystem::class);
    }



    /**
     * 
     *  Shared semua path application ke container.
     *
     *  @return void 
     **/
    protected function sharedPathsInContainer(): void
    {
        $this->container->shared("path.base", $this->basePath());
        $this->container->shared("path.app", $this->appPath());
        $this->container->shared("path.bootstrap", $this->bootstrapPath());
        $this->container->shared("path.public", $this->publicPath());
        $this->container->shared("path.resources", $this->resourcesPath());
        $this->container->shared("path.storage", $this->storagePath());
    }



    /**
     *  
     *  Dapatkan base path directory application.
     *  
     *  @return string 
     **/
    public function basePath(): string
    {
        return $this->join_path();
    }



    /**
     *
     *  Dapatkan path directory app.
     *
     *  @return string 
     **/
    public function appPath(): string
    {
        return $this->join_path("/app");
    }



    /**
     *  
     *  Dapatkan path directory bootstrap.
     *
     *  @return string 
     **/
    public function bootstrapPath(): string
    {
        return $this->join_path("/bootstrap");
    }



    /**
     *
     *  Dapatkan path directory public.
     *
     *  @return string 
     **/
    public function publicPath(): string
    {
        return $this->join_path("/public");
    }



    /**
     *
     *  Dapatkan path directory resources.
     *
     *  @return string 
     **/
    public function resourcesPath(): string
    {
        return $this->join_path("/resources");
    }



    /**
     *
     *  Dapatkan path directory storage.
     *
     *  @return string 
     **/
    public function storagePath(): string
    {
        return $this->join_path("/storage");
    }



    /**
     *
     *  Gabungkan base path dengan path baru.
     *
     *  @return string
     **/
    protected function join_path(string $path = ""): string
    {
        return $this->basePath . $path;
    }



    /**
     * 
     *  Register page error handler.
     *
     *  @param void 
     **/
    protected function registerPageErrorHandler(): void
    {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }


    
    /**
     *
     *  Register helper function.
     *  
     *  @return void
     **/
    public function registerHelperFunction(): void
    {
        require __DIR__ . "/../helpers.php";
    }
}
