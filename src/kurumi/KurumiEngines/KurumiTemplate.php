<?php


namespace Kurumi\KurumiEngines;


use Kurumi\Container\Container;
use Kurumi\Views\View;


/**
 *
 *  Class KurumiTemplate bertanggung jawab 
 *  untuk mengatur kebutuhan template.
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
final class KurumiTemplate extends KurumiEngine implements KurumiEngineInterface
{
        
   
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
    public function __construct()
    {

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

        if (!is_null($value)) self::$contents[$name] = $value;
        return ob_start();
    }



    /**
     *
     *  Menyimpan content dan sebagai pembatas content
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
        $this->render($path, $data);
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
        $this->render($path);
    }



    /**
     *  
     *  Membuat bahan untuk keperluan method
     *  importFile.
     *  
     *  @param string $view 
     *  @param string $path 
     *  @return array 
     **/
    protected function makeImportFileMaterials(string $view, string $path): array
    {
        $relativeFolder = array_slice(explode('/', $view), 0, -1);
        $pathSourceFile = PATH_VIEWS . join('/', $relativeFolder) . '/' . $path;
        $fileExtensions = array_slice(explode('.', $path), -1)[0];

        return [
            "relativeFolder" => join('.', $relativeFolder),
            "pathInputFile" => $pathSourceFile,
            "fileExtensions" => $fileExtensions
        ];
    }



    /**
     *
     *  Membuat folder dan file output untuk method 
     *  importFile jika tidak ditemukan, dan kembalikan
     *  path file output nya.
     * 
     *  @param string $pathDirectory
     *  @param string $fileExtension 
     *  @return string
     **/
    protected function makeImportFileOutput(string $pathDirectory, string $fileExtension): string
    {
        $destinationDirectory = $pathDirectory . $fileExtension . '/';
        if (!file_exists($destinationDirectory)) {
            mkdir($destinationDirectory, 0777, true);
        }

        $pathOutputFile = $destinationDirectory . 'app.' . $fileExtension;
        if (!file_exists($pathOutputFile)) {
            file_put_contents($pathOutputFile, '');
        }

        return $pathOutputFile;
    }



    /**
     *  
     *  Membuat file baru (output) dengan key yang 
     *  diberikan, jika terdapat perubahan pada
     *  file (input) maka ubah file (output) nya
     *  sesuai dengan key nya.
     *  
     *  @param string $pathOutputFile
     *  @param string $fileExtensions
     *  @param string $inputContentFile
     *  @param string $outputContentFile
     *  @param string $key 
     **/
    private function handlerImportFile(
        string $pathOutputFile,
        string $fileExtensions,
        string $inputContentFile,
        string $outputContentFile,
        string $key,
    )
    {

        $pattern   = "/\/\*\*\[\b$key\b\]\*\*\/|\/\*\*\[\bend$key\b\]\*\*\//";
        $markerKey = "";
 
        preg_match_all($pattern, $outputContentFile, $matches, PREG_OFFSET_CAPTURE);

        if ($fileExtensions === "js") {
            $markerKey = "{/**[$key]**/\n\n{$inputContentFile}\n/**[end$key]**/}\n\n";
        } else {
            $markerKey = "/**[$key]**/\n\n{$inputContentFile}\n/**[end$key]**/\n\n";
        }

        if (empty($matches[0])) {
            return file_put_contents($pathOutputFile, $markerKey, FILE_APPEND);
        }

        foreach ($matches[0] as $i => $match) 
        {
            if (strpos($match[0], "end$key") === false && isset($matches[0][$i + 1]) && strpos($matches[0][$i + 1][0], "end$key") !== false)
            {
                $startOffset = $match[1] + strlen($match[0]);
                $endOffset = $matches[0][$i + 1][1];

                $beforeSection = substr($outputContentFile, 0, $startOffset);
                $afterSection  = substr($outputContentFile, $endOffset);

                $newContentFile = $beforeSection . "\n\n$inputContentFile\n" . $afterSection;
                $i++;
            }
        }

        file_put_contents($pathOutputFile, $newContentFile);
    }



    /**
     *  
     *  Memungkinkan untuk menulis file css atau js 
     *  secara terpisah dan digenerate menjadi satu file.
     *   
     *  @param string $view 
     *  @param string $path 
     *  @return void 
     **/
    public function importFile(string $view, string $path, string $key = null): void
    {            
        $materials = $this->makeImportFileMaterials($view, $path);

        $pathOutputFile = $this->makeImportFileOutput(
            pathDirectory: PATH_PUBLIC,
            fileExtension: $materials["fileExtensions"]
        );
        
        $getInputContentFile  = parent::getFileContent($materials["pathInputFile"]);
        $getOutputContentFile = parent::getFileContent($pathOutputFile);
        
        $this->handlerImportFile(
            key: $key ?? $materials["relativeFolder"],
            pathOutputFile: $pathOutputFile,
            fileExtensions: $materials["fileExtensions"],
            inputContentFile: $getInputContentFile,
            outputContentFile: $getOutputContentFile
        );  
    }
    


    /**
     *  
     *  Render file/tampilkan view.
     *  
     *  @param string $path 
     *  @param array  $data 
     *  @return void 
     **/
    public function render(string $path, array $data = []): void
    {
        Container::getInstance()
            ->make(View::class)
            ->setBasePath(PATH_STORAGE_PUBLIC)
            ->render($path, $data);
    }
}
