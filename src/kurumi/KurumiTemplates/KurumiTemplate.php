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
class KurumiTemplate implements KurumiTemplateInterface {

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
     *  Memungkinkan untuk menulis file css atau js 
     *  secara terpisah dan dicompile/generate
     *  menjadi satu file.
     *   
     *  @param string $view 
     *  @param string $path 
     *  @return void 
     **/
    public function importFile(string $view, string $path, string $key = null): void
    {
            
        $relativeFolder = explode('/', $view)[0];
        $pathSourceFile = PATH_VIEWS . $relativeFolder . '/' . $path;
        $key = $key ?? $relativeFolder;
        $fileExtension  = explode('.', $path)[1];

        $this->handlerImportFile(
            $pathSourceFile,
            $key,
            $fileExtension,
            PATH_PUBLIC
        );  
    }



    /**
     *
     *  @method importFile()
     *
     *  @param string $path 
     *  @param string $key 
     *  @param string $fileExtension 
     *  @param string $generateTo
     *  @return void 
     *
     **/
    private function handlerImportFile(string $path, string $key, string $fileExtension, string $generateTo)
    {

        $startKey = "/\/\*\*\[\b$key\b\]\*\*\//";
        $endKey = "/\/\*\*\[\bend$key\b\]\*\*\//";

        $destinationDirectory = $generateTo . $fileExtension . '/';
        $pathGenerateFile = $destinationDirectory . 'app.' . $fileExtension;

        if (!file_exists($path)) return throw new Exception("($path) file tidak ditemukan.");
        if (!file_exists($destinationDirectory)) mkdir($destinationDirectory, 0777, true);
        if (!file_exists($pathGenerateFile)) file_put_contents($pathGenerateFile, '');

        $getSourceContentFile   = file_get_contents($path);
        $getGenarateContentFile = file_get_contents($pathGenerateFile);

        preg_match_all($startKey, $getGenarateContentFile, $startMatches, PREG_OFFSET_CAPTURE);
        preg_match_all($endKey, $getGenarateContentFile, $endMatches, PREG_OFFSET_CAPTURE);

        if (empty($startMatches[0]) || empty($endMatches[0])) {
            return file_put_contents(
                $pathGenerateFile, 
                "/**[$key]**/\n\n{$getSourceContentFile}\n/**[end$key]**/\n\n",
                FILE_APPEND
            );                
        }

        $startOffsets = array_column($startMatches[0], 1);
        $endOffsets   = array_column($endMatches[0], 1);

        foreach ($startOffsets as $index => $startOffset) {               
            $endOffset = $endOffsets[$index];      
            $beforeSection  = substr($getGenarateContentFile, 0, $startOffset + strlen($startMatches[0][$index][0]));
            $afterSection   = substr($getGenarateContentFile, $endOffset);
            $newContentFile = $beforeSection . "\n\n$getSourceContentFile\n" . $afterSection;
        }

        file_put_contents($pathGenerateFile, $newContentFile);
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
        global $container;
        $container->make('View')->render($path, $data);
    }
}
