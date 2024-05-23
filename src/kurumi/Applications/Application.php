<?php


namespace Kurumi\Applications;


use Kurumi\Views\View;
use Kurumi\Consoles\Command;
use Kurumi\Container\Container;
use Kurumi\KurumiEngines\KurumiTemplate;
use Kurumi\KurumiEngines\KurumiDirective;


/**
 *
 *  Class yang bertanggung jawab atas
 *  configurasi awal framework.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class Application {


    
    public function __construct()
    {
        $this->registerPageErrorHandler();
        $this->registerClassBindings();
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
        $container = Container::getInstance();
        $container->bind(View::class);
        $container->bind(KurumiTemplate::class);
        $container->bind(KurumiDirective::class);
        $container->bind(Command::class);
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
