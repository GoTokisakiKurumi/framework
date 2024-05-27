<?php


namespace Kurumi\Views;


use Whoops\Exception\ErrorException;
use Kurumi\KurumiEngines\
{ 
    KurumiEngine,
    KurumiDirectiveInterface,
    KurumiTemplateInterface
};


/**
 *
 *  Class yang bertanggung jawab atas kebutuhan
 *  menampilkan file.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class View extends KurumiEngine 
{

    /**
     *
     *  Menyimpan base path.
     *  
     *  @property-read string $basePath
     **/
    public readonly string $basePath;



    /**
     *
     *  Menginisialisasi property 
     *
     *  @property-read KurumiTemplateInterface  $kurumiTemplate 
     *  @property-read KurumiDirectiveInterface $kurumiDirective
     **/
    public function __construct(
        protected readonly KurumiTemplateInterface  $kurumiTemplate,
        protected readonly KurumiDirectiveInterface $kurumiDirective,
    ){}



    /**
     * 
     *  Set base path ke property $basePath.
     *  
     *  @param string $path 
     *  @retrun object 
     **/
    public function setBasePath(string $path): View
    {
        $this->basePath = $path;
        return $this;
    }



    /**
     *  
     *  Validasi file, apakah file ada atau tidak ada 
     *  apakah file berextension .kurumi.php atau tidak  
     *  
     *  @param string $view 
     *  @throws \Exception jika file tidak berextension .kurumi.php
     *  @throws \Exception jika file tidak ditemukan 
     *  @return void 
     **/
    protected function validateViews(string $view): void
    {
        $pathSourceViews = PATH_VIEWS . $view . parent::DEFAULT_FILE_EXTENSION;
        if (file_exists(str_replace('.kurumi.php', '.php', $pathSourceViews))) {
            throw new ErrorException("($view.php) Sepertinya kamu melupakan namaku?");
        } elseif (!file_exists($pathSourceViews)) {
            throw new ErrorException("Tampaknya file ($view) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }
    }



    /**
     *  
     *  Terjemahkan directive menjadi php biasa.
     *
     *  @param string $view
     *  @return void 
     **/
    public function compile(string $view): void
    {
        $directive = $this->kurumiDirective;
        $directive->setDirectory(input: PATH_VIEWS, output: $this->basePath);
        $directive->files($view);
        $directive->compile();
    }



    /**
     *  
     *  Mengembalikan instace object kurumiTemplate.
     *
     *  @return KurumiTemplateInterface 
     **/
    public function template(): KurumiTemplateInterface
    {
        $template = $this->kurumiTemplate;
        $template->setView($this);
        return $template;
    }



    /**
     *
     *  Dapatkan path storage.
     *  
     *  @param string $view 
     *  @return string 
     **/
    public function getPathStorage(string $view): string
    {
        return $this->basePath . pathToDot($view) . '.php';
    }



    /**
     *
     *  Proses merender file, atau menampilkan file 
     *
     *  @param string $view 
     *  @param array  $data
     *  @return void 
     **/
    public function render(string $view, array $data = [])
    {
        $this->validateViews($view);
        $this->compile($view);

        $data = array_merge([
            "__temp" => $this->template(),
            "__view" => $view
        ], $data);

        app('filesystem')->require($this->getPathStorage($view), $data);
    }
}
