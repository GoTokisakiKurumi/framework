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
 *  Class khusus kebutuhan menangani views.
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
     *  menginisialisasi property 
     *
     *  @param string $basePath 
     *  @param ContainerInterface $container
     **/
    public function __construct(
        KurumiTemplateInterface  $kurumiTemplate,
        KurumiDirectiveInterface $kurumiDirective,
        string $basePath
    ){
        $this->kurumiDirective = $kurumiDirective;
        $this->kurumiTemplate  = $kurumiTemplate;
        $this->basePath = $basePath;
    }



    /**
     *  
     *  Validasi file, apakah file ada atau tidak ada 
     *  apakah file berextension .kurumi.php atau tidak  
     *  
     *  @param string $view 
     *  @throws \Exception jika file tidak berextension .kurumi.php
     *  @throws \Exception jika file tidak ditemukan 
     *  @return bool 
     **/
    protected function validateViews(string $view)
    {
        $pathSourceViews = PATH_VIEWS . $view . self::DEFAULT_FILE_EXTENSION;
        if (file_exists(str_replace('.kurumi.php', '.php', $pathSourceViews))) {
            throw new Exception("($view.php) Sepertinya kamu melupakan namaku?");
        } elseif (!file_exists($pathSourceViews)) {
            throw new Exception("Tampaknya file ($view) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }
    }



    /**
     *
     *  Proses merender file, atau menampilkan file 
     *
     *  @param string $view 
     *  @param array $data
     *  @return void 
     **/
    public function render(string $view, array $data = []): void
    {
        $viewPathStorage = $this->basePath . pathToDot($view)  . '.php';
        $this->validateViews($view);
        $this->kurumiDirective->render($view);

        $template = $this->kurumiTemplate;
        extract($data);
        include_once $viewPathStorage;
    }
}
