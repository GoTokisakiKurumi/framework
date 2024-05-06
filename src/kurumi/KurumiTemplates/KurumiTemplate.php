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
     *  yang sesuai dengan $name 
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
     *
     **/
    public function includeFile(string $path)
    {
        $pathInclude = PATH_VIEWS . $path . self::DEFAULT_FILE_EXTENSION;

        if (!file_exists($pathInclude)) {
            throw new Exception("($path) File tidak ditemukan.");
        }
        
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
        try {
            $this->directive->render($path);
            include PATH_STORAGE . 'app/' . pathToDot($path) . '.php';
        } catch (\Exception) {
            throw new \Exception("Kurumi: Tampaknya file ($path) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }
    }
}
