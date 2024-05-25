<?php


namespace Kurumi\Applications;


use Kurumi\Views\View;
use Kurumi\Consoles\Command;
use Kurumi\Container\ContainerInterface;
use Kurumi\FileSystems\FileSystem;
use Kurumi\KurumiEngines\KurumiTemplate;
use Kurumi\KurumiEngines\KurumiDirective;
use Kurumi\KurumiEngines\KurumiDirectiveInterface;
use Kurumi\KurumiEngines\KurumiTemplateInterface;


/**
 *
 *  Class yang bertanggung jawab atas
 *  configurasi awal framework.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class Application {


    
    public function __construct(
        protected ContainerInterface $container
    )
    {
        $this->registerClassBindings();
        $this->registerClassSupports();
        $this->registerHelperFunction();
        $this->registerPageErrorHandler();
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
        $this->container->bind(KurumiDirectiveInterface::class, KurumiDirective::class);
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
