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
     *  Default file extension.
     *
     **/
    const DEFAULT_FILE_EXTENSION = '.kurumi.php';

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

        $viewPath = $this->basePath . 'app/' . $view  . '.php';
        $pathSourceViews = PATH_VIEWS . $view . self::DEFAULT_FILE_EXTENSION;

        if (file_exists($pathSourceViews)) {
            $template = $container->make('KurumiTemplate');
            $tranfrom = $container->make('KurumiTransform');
            $tranfrom->render($view);
            extract($data);

            include_once $viewPath;
        } else {
            throw new \Exception("Kurumi: Tampaknya file ($view) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }
    }
}
