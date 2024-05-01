<?php

namespace Kurumi\Utils;

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
     *
     *  proses merender files.
     *
     *  @param string $view 
     *  @param array $data
     **/
    public function render(string $view, array $data = [])
    {

        $viewPath = $this->basePath . 'app/' . $view  . '.php';
        $pathSourceViews = PATH_VIEWS . $view . self::DEFAULT_FILE_EXTENSION;

        if (file_exists($pathSourceViews)) {
            $template = $this->container->make('KurumiTemplate');
            $tranfrom = $this->container->make('KurumiTransform');
            $tranfrom->render($view);
            extract($data);

            include_once $viewPath;
        } else {
            throw new \Exception("Kurumi: Tampaknya file ($view) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }
    }
}
