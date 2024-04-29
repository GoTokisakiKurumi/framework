<?php

namespace Kurumi\Utils;

/**
 *
 *
 *  Class khusus kebutuhan menangani tampilan.
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
class View
{
    /**
     *
     *  @property string $basePath 
     *  
     *  menyimpan path dasar folder.
     *
     **/
    protected string $basePath;

    /**
     *
     *  @method __construct()
     *
     *  menginisialisasi property 
     *
     *  @param string $basePath 
     *
     **/
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     *
     *  @method render()
     *
     *  proses merender files.
     *
     *  @param string $view 
     *  @param array $data
     * 
     **/
    public function render(string $view, array $data = [])
    {
        global $container;

        $viewPath = $this->basePath . $view . '.kurumi.php';
        $template = $container->make('KurumiTemplate');

        if (file_exists($viewPath)) {
            extract($data);
            include_once $viewPath;
        } else {
            throw new \Exception('View file not found: ' . $view);
        }
    }
}
