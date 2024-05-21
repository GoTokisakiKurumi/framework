<?php


namespace Kurumi\Applications;


use Kurumi\Views\View;
use Kurumi\Consoles\Command;
use Kurumi\Container\Container;
use Kurumi\KurumiEngines\KurumiTemplate;
use Kurumi\KurumiEngines\KurumiDirective;


class Application extends Container {


    
    public function __construct()
    {
        $this->registerPageErrorHandler();
        $this->registerClassBindings();
        $this->registerFunction();
    }



    /**
     *  
     *  Register class ke container.
     *
     *  @return void 
     **/
    protected function registerClassBindings(): void
    {
        $this->bind(View::class);
        $this->bind(KurumiTemplate::class);
        $this->bind(KurumiDirective::class);
        $this->bind(Command::class);
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
     *  Register function.
     *  
     *  @return void
     **/
    public static function registerFunction(): void
    {
        require_once __DIR__ . "/../Utils/func/view.php";
        require_once __DIR__ . "/../Utils/func/define.php";
        require_once __DIR__ . "/../Utils/func/pathToDot.php";
    }
}
