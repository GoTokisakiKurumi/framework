<?php

namespace Kurumi;

use Exception;
use Kurumi\KurumiTemplates\
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
    protected function handlerViews(string $view): object
    {
        $pathSourceViews = PATH_VIEWS . $view . self::DEFAULT_FILE_EXTENSION;
        if (file_exists($pathSourceViews)) {
            $template  = $this->kurumiTemplate;
            $directive = $this->kurumiDirective;
            $directive->render($view);
            
          return $template;
        } elseif (file_exists(str_replace('.kurumi.php', '.php', $pathSourceViews))) {
            throw new Exception("($view.php) Sepertinya kamu melupakan namaku?");
        } else {
            throw new Exception("Tampaknya file ($view) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
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
