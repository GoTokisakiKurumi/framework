<?php

namespace Kurumi\KurumiTemplates;

use Exception;

/**
 *
 *
 *  class KurumiTemplate 
 *
 *  untuk mengatur kebutuhan layouting.
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
class KurumiTemplate {

    /**
     * 
     *  Default file extension.
     *
     **/
    const DEFAULT_FILE_EXTENSION = '.kurumi.php';

    
    /**
     *  
     *  @property $transform 
     *
     *  Menyimpan object dari class KurumiTransform.
     **/
    protected KurumiDirectiveInterface $directive;
        
   
    /**
     *  
     *  menyimpan nama layout yang akan digunakan.
     *
     *  @property array $nameContents 
     **/
    protected static array $nameContents = [];


    /**
     *
     *  menyimpan isi content.
     *
     *  @property array $buffer 
     **/
    protected static array $contents = [];


    /**
     *
     *  menyimpan key.
     *
     *  @property array $key 
     **/
    protected static string $key = ""; 


    /**
     *
     *   @method __construct()
     *
     *  Inisialisasi property DI.
     **/
    public function __construct(KurumiDirectiveInterface $directive)
    {
        $this->directive = $directive;
    }
    


    /**
     *  
     *  Menghandle contents dan menampilkan contents
     *  yang sesuai dengan param $name 
     *
     *  @param string $name 
     *  @return void 
     **/
    public function content(string $name): void
    {
        if (array_key_exists($name, self::$nameContents)) {
            if (self::$nameContents[$name] === $name) {
                echo self::$contents[$name];
            } 
        }
    }



    /**
     *  
     *  
     *  Memulai content yang akan ditampilkan.
     *  
     *  @param string $name 
     *  @param string $value opsional 
     *  @return string 
     **/
    public function startContent(string $name, string $value = ''): string
    {
        self::$nameContents[$name] = $name;
        self::$key = $name;

        if (!is_null($value)) {
            self::$contents[$name] = $value;
        }

        return ob_start();
    }



    /**
     *
     *  Menyimpan Content dan sebagai pembatas content
     *  yang disimpan.
     *
     *  @return void  
     **/
    public function stopContent(): void
    {
        self::$contents[self::$key] = ob_get_clean();
    }



    /**
     *  
     *  Include files.
     *
     *  @param string $path 
     *  @return void 
     **/
    public function includeFile(string $path, array $data = []): void
    {
        $this->renderDirective($path, $data);
    }


    
    /**
     *
     *  Memanggil file yang akan menjadi layout/parent.
     *
     *  @param string $path 
     *  @return void 
     *  @throws jika file tidak ditemukan.
     **/
    public function extendContent(string $path): void
    {
        $this->renderDirective($path);
    }



    /**
     *  
     *  Generate file syntax directive menjadi file 
     *  file syntax php biasa. 
     *
     *  file directive ->  (.*).kurumi.php 
     *  
     *  @param string $path 
     **/
    public function renderDirective(string $path, array $data = []): void
    {
        $pathFileDirective = PATH_VIEWS . $path . self::DEFAULT_FILE_EXTENSION;
        if (!file_exists($pathFileDirective)) {
            throw new \Exception("Kurumi: Tampaknya file ($path) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }

        extract($data); // > 0 or 1
        $this->directive->render($path); // > null
        include_once PATH_STORAGE . 'app/' . pathToDot($path) . '.php'; 
    }
}
