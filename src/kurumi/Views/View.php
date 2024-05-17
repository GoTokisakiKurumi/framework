<?php


namespace Kurumi\Views;


use Exception;
use Kurumi\KurumiEngines\
{ 
    KurumiEngine,
    KurumiDirectiveInterface,
    KurumiTemplateInterface
};


/**
 *
 *  Class View yang bertanggung jawab atas kebutuhan
 *  menampilkan file.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class View extends KurumiEngine 
{


    /**
     *
     *  menginisialisasi property 
     *
     *  @property-read KurumiTemplateInterface  $kurumiTemplate 
     *  @property-read KurumiDirectiveInterface $kurumiDirective
     *  @property-read string $basePath 
     **/
    public function __construct(
        protected readonly KurumiTemplateInterface  $kurumiTemplate,
        protected readonly KurumiDirectiveInterface $kurumiDirective,
        protected readonly string $basePath
    )
    {

    }



    /**
     *  
     *  Validasi file, apakah file ada atau tidak ada 
     *  apakah file berextension .kurumi.php atau tidak  
     *  
     *  @param string $view 
     *  @throws \Exception jika file tidak berextension .kurumi.php
     *  @throws \Exception jika file tidak ditemukan 
     **/
    protected function validateViews(string $view)
    {
        $pathSourceViews = PATH_VIEWS . $view . parent::DEFAULT_FILE_EXTENSION;
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
     *  @param array  $data
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
