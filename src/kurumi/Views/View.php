<?php

namespace Kurumi\Views;

use Exception;
use Kurumi\KurumiEngines\
{ 
    KurumiDirectiveInterface,
    KurumiTemplateInterface
};


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
     *  Menyimpan class instance KurumiTemplate
     **/
    protected KurumiTemplateInterface $kurumiTemplate;


    /**
     *  
     *  Menyimpan class instance KurumiDirective 
     **/
    protected KurumiDirectiveInterface $kurumiDirective;


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
    public function __construct(
        KurumiTemplateInterface  $kurumiTemplate,
        KurumiDirectiveInterface $kurumiDirective,
        string $basePath)
    {
        $this->kurumiDirective = $kurumiDirective;
        $this->kurumiTemplate  = $kurumiTemplate;
        $this->basePath = $basePath;
    }



    /**
     *  
     *  handler views 
     *  
     *  @param string $view 
     *  @return object 
     **/
    protected function handlerViews(string $view): bool
    {
        $pathSourceViews = PATH_VIEWS . $view . self::DEFAULT_FILE_EXTENSION;
        if (file_exists(str_replace('.kurumi.php', '.php', $pathSourceViews))) {
            throw new Exception("($view.php) Sepertinya kamu melupakan namaku?");
        } elseif (!file_exists($pathSourceViews)) {
            throw new Exception("Tampaknya file ($view) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }

        return true;
    }



    /**
     *
     *
     *  proses merender files.
     *
     *  @param string $view 
     *  @param array $data
     **/
    public function render(string $view, array $data = []): void
    {
        $viewPathStorage = $this->basePath . 'app/' . pathToDot($view)  . '.php';
        $isFileExist = $this->handlerViews($view);
        
        if ($isFileExist) {
            $template  = $this->kurumiTemplate;
            $directive = $this->kurumiDirective;
            $directive->render($view);
            extract($data);

            include_once $viewPathStorage;
        }
        
    }
}
