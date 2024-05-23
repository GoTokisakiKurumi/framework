<?php


namespace Kurumi\Applications;


use Kurumi\Views\View;
use Kurumi\Consoles\Command;
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


    
    public function __construct()
    {
        $this->registerPageErrorHandler();
        $this->registerHelperFunction();
        $this->registerClassBindings();
    }



    /**
     *  
     *  Register class ke container.
     *
     *  @return void 
     **/
    protected function registerClassBindings(): void
    {
        $container = app();
        $container->bind(View::class);
        $container->bind(KurumiTemplateInterface::class, KurumiTemplate::class);
        $container->bind(KurumiDirectiveInterface::class, KurumiDirective::class);
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
