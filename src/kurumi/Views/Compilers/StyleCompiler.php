<?php


namespace Kurumi\Views\Compilers;


use Whoops\Exception\ErrorException;


/**
 *  
 *  Class yang bertanggung jawab atas
 *  compiler style (css, js)
 *
 *  @author Lutfi Aulia Sidik
 **/
final class StyleCompiler extends Compiler implements CompilerInterface {
 

    /**
     *  
     *  Default nama file yang digunakan 
     *  untuk nama file hasil compile.
     *
     *  @property string $filename
     **/
    protected string $filename = "app";


    /**
     * 
     *  Menyimpan jalur file yang akan 
     *  dicompile.
     *  
     *  @property string $pathInput
     **/
    protected string $pathInput = "";


    /**
     *   
     *  Menyimpan jalur file yang telah
     *  dicompile.
     *
     *  @property string $pathOutput
     **/
    protected string $pathOutput = "";


    /**
     * 
     *  Menyimpan key.
     *
     *  @property string $key
     **/
    protected ?string $key = null;

    
    /**
     *  
     *  Extension file yang didukung.
     *
     *  @property array $extension
     **/
    protected array $extension = ["css", "js"];



    /**
     *  
     *  Mulai untuk mengcompile file.
     *
     *  @param string      $path
     *  @param string|null $key
     *  @throws ErrorException jika file tidak ditemukan
     *  @return void 
     **/
    public function compile(string $path, string $key = null): void
    {
        if ($this->files->exists($path)) {
            $this->setPathInput($path);
            $this->setPathOutput($this->createDirectoryOutput());
            
            $pathOutput = $this->getPathOutput();
            if(!$this->files->exists($pathOutput)) {
                $this->files->put($pathOutput);
            }
      
            (is_null($key)) ? $this->setKey($this->createKey())
                            : $this->setKey($key);
            

            $this->compileString();

        } else {
            throw new ErrorException("File tidak ditemukan: $path");
        }
    }



    /**
     * 
     *  Process compiler file.
     *
     *  @return mixed 
     **/
    protected function compileString()
    {
        $pathOutput    = $this->getPathOutput();
        $contentInput  = $this->files->get($this->getPathInput());
        $contentOutput = $this->files->get($pathOutput);
    
        preg_match_all($this->getPattern(), $contentOutput, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return $this->files->put(
                $pathOutput,
                $this->getMarkerKey($contentInput),
                FILE_APPEND
            );
        }

        $key = $this->getKey();

        foreach ($matches[0] as $i => $match) {
            if (strpos($match[0], "end$key") === false && isset($matches[0][$i + 1]) && strpos($matches[0][$i + 1][0], "end$key") !== false)
            {
                $startOffset = $match[1] + strlen($match[0]);
                $endOffset   = $matches[0][$i + 1][1];

                $beforeSection = substr($contentOutput, 0, $startOffset);
                $afterSection  = substr($contentOutput, $endOffset);

                $newContentFile = $beforeSection . "\n\n$contentInput\n" . $afterSection;

                $i++;
            }
        }
        

        if (isFileUpdate($this->getPathInput(), $pathOutput)) {
            $this->files->put($pathOutput, $newContentFile);
        }
    }



    /**
     * 
     *  Membuat folder output dan mengembalikan
     *  path directory output.
     *
     *  @return string 
     **/
    protected function createDirectoryOutput(): string
    {
        $path = $this->getDirectoryOutput() . "/" .  $this->getExtension();
        if (!$this->files->exists($path)) {
            $this->files->makeDir($path);
        }

        return $path;
    }


    
    /**
     *
     *  Membuat default key.
     *
     *  @return string
     **/
    protected function createKey(): string
    {
        $dir = dirname($this->getPathInput());
        $key = join('.', array_slice(explode('/', $dir), 1, 15));

        return $key;
    }



    /**
     *
     *  Set Key.
     *  
     *  @param string $key 
     *  @return void 
     **/
    protected function setKey(string $key): void
    {
        $this->key = $key;
    }



    /**
     *   
     *  Set jalur directory output untuk menyimpan 
     *  hasil compile.
     *
     *  @param string $directory
     *  @return void 
     **/
    public function setDirectoryOutput(string $directory) 
    {
        $this->directoryOutput = $directory;
    }



    /**
     * 
     *  Set jalur file yang akan dicompile.
     *
     *  @param string $path
     *  @return void 
     **/
    protected function setPathInput(string $path): void
    {
        $this->pathInput = $path;
    }



    /**
     * 
     *  Set jalur file output yang telah dicompile.
     *
     *  @param string $path
     *  @return void 
     **/
    protected function setPathOutput(string $path): void
    {
        $path = $path . "/" . $this->getFilename()
                      . "." . $this->getExtension();

        $this->pathOutput = $path;
    }



    /**
     * 
     *  Dapatkan path input.
     *
     *  @return string
     **/
    public function getPathInput(): string
    {
        return $this->pathInput;
    }



    /**
     * 
     *  Dapatkan path output.
     *
     *  @return string 
     **/
    public function getPathOutput(): string
    {
        return $this->pathOutput;
    }



    /**
     *  
     *  Dapatkan default nama file yang
     *  digunakan untuk nama file hasil compile.
     *
     *  @return string 
     **/
    public function getFilename(): string
    {
        return $this->filename;
    }



    /**
     * 
     *  Dapatkan key.
     *
     *  @return 
     **/
    public function getKey(): string
    {
        return $this->key;
    }



    /**
     * 
     *  Dapatkan file content yang sudah
     *  ditambahkan key.
     *  
     *  @param string $content
     *  @return string
     **/
    protected function getMarkerKey(string $content): string
    {
        $key = $this->getKey();
        $marker = "/**[$key]**/\n\n{$content}\n/**[end$key]**/\n\n";

        return $marker;
    }



    /**
     * 
     *  Dapatkan pattern key untuk kebutuhan
     *  regexp.
     *
     *  @return string 
     **/
    protected function getPattern(): string
    {
        $key = $this->getKey();
        $pattern = "/\/\*\*\[\b{$key}\b\]\*\*\/|\/\*\*\[\bend{$key}\b\]\*\*\//";

        return $pattern;
    }



    /**
     *
     *  Dapatkan extension file yang akan dicompile,
     *  dan cek apakah file extension nya didukung.
     *  
     *  @throws Exception jika extension file tidak didukung
     *  @return string
     **/
    public function getExtension(): string
    {
        $extension = array_slice(explode(".", $this->getPathInput()), -1)[0]; 
        $exteIndex = array_search($extension, $this->extension);
        if ($exteIndex !== false) {
            return $this->extension[$exteIndex];
        }
        
        throw new ErrorException("Extension [$extension] tidak didukung.");
    }
}
