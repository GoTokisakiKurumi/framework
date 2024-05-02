<?php

namespace Kurumi;

use Kurumi\Container\ContainerInterface;

/**
 *
 *
 *  Class khusus kebutuhan menangani tampilan.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class View
{

    /**
     * 
     *  Default file extension.
     **/
    const DEFAULT_FILE_EXTENSION = '.kurumi.php';

    /**
     *
     *  @property ContainerInterface $container 
     *
     *  menyimpan objek container.
     **/
    protected ContainerInterface $container;

    /**
     *
     *  @property string $basePath 
     *  
     *  menyimpan path dasar folder.
     **/
    protected readonly string $basePath;

    /**
     *
     *
     *  menginisialisasi property 
     *
     *  @param string $basePath 
     *  @param ContainerInterface $container
     **/
    public function __construct(ContainerInterface $container, string $basePath)
    {
        $this->container = $container;
        $this->basePath = $basePath;
    }


    /**
     *  
     *  handler views 
     *  
     *  @param string $view 
     *  @return object 
     **/
    protected function handlerViews(string $view): object
    {
        $pathSourceViews = PATH_VIEWS . $view . self::DEFAULT_FILE_EXTENSION;
        if (file_exists($pathSourceViews)) {
            $template  = $this->container->make('KurumiTemplate');
            $directive = $this->container->make('KurumiDirective');
            $directive->render($view);
            
          return $template;
        } elseif (file_exists(str_replace('.kurumi.php', '.php', $pathSourceViews))) {
            throw new \Exception("($view.php) Sepertinya kamu melupakan namaku?");
        } else {
            throw new \Exception("Tampaknya file ($view) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }

    }

    /**
     *
     *
     *  proses merender files.
     *
     *  @param string $view 
     *  @param array $data
     **/
    public function render(string $view, array $data = [])
    {

        $viewPathStorage = $this->basePath . 'app/' . pathToDot($view)  . '.php';
        $template = $this->handlerViews($view);

        if (is_object($template)) {
            extract($data);
            include_once $viewPathStorage;
        }
        
    }
}
