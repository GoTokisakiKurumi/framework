<?php

namespace Kurumi\Views\Compilers;


use Exception;
use Kurumi\Views\Engines\KurumiEngine;


/**
 *
 *  Class KurumiCompiler yang bertanggung jawab 
 *  atas semua hal yang mengenai compiler.
 *
 *  @author Lutfi Aulia Sidik
 **/
final class KurumiCompiler extends KurumiEngine implements KurumiCompilerInterface
{

    use Traits\CompilesLayouts,
        Traits\CompilesIncludes,
        Traits\CompilesRawPhp,
        Traits\CompilesLoops,
        Traits\CompilesFunctions,
        Traits\CompilesEchos;


    /**
     * 
     *  Menyimpan directory input.
     *
     *  @property string $directoryInput
     **/
    private string $directoryInput = "";

    
    /**
     * 
     *  Menyimpan directory output.
     *
     *  @property string $directoryOutput
     **/
    private string $directoryOutput = "";


    /**
     *  
     *  Menyimpan directive yang akan 
     *  selalu dibawah.
     *
     *  @property array $footer
     **/
    protected array $footer = [];


    /**
     *  
     *  Menyimpan content file yang sudah
     *  dicompile.
     *
     *  @property string $content
     **/
    protected string $content = "";


    /**
     *  
     *  Menyimpan jalur file yang akan
     *  dicompile.
     *  
     *  @property string $pathView
     **/
    protected string $pathView = "";



    /**
     *  
     *
     **/
    public function __construct(){}


    
    /**
     * 
     *  Set directory input dan output.
     *
     *  @param string $input
     *  @param string $output
     *  @return object 
     **/
    public function setDirectory(string $input, string $output): object
    {
        $this->directoryInput  = $input;
        $this->directoryOutput = $output;

        return $this;
    }



    /**
     *  
     *  Dapatkan content files.
     *  
     *  @param string $path
     *  @return string 
     **/
    protected function getFileContent(string $path): string
    {
        $pathFile = $this->directoryInput . $path . parent::DEFAULT_FILE_EXTENSION;

        return parent::getFileContent($pathFile);
    }



    /**
     *
     *  Validasi folder input dan output jika 
     *  folder output tidak ditemukan maka buat.
     *
     *  @throw \Exception jika folder input tidak ditemukan.
     *  @throw \Exception jika folder output tidak ditemukan.
     *  @return void 
     **/
    private function validateDirectory(): void
    {
        if (!file_exists($this->directoryInput) || !is_writable($this->directoryInput)) {
            throw new Exception("Direktori input tidak valid: {$this->directoryInput}");
        } elseif (@$this->directoryOutput[-1] !== "/") {
            throw new Exception("Direktori output tidak valid: {$this->directoryOutput}");
        } elseif (!file_exists($this->directoryOutput)) {
            mkdir($this->directoryOutput, 0777, true);
        }
    }



    /**
     * 
     *  Compile directive menjadi php valid.
     *
     *  @param string $content
     *  @return string
     **/
    private function toPhpValid(string $content): string
    {
        foreach (
        [
            $this->compilesEchos() => '/{{\s*(.*?)\s*}}/',
            $this->compilesEcho() => '/{!\s*(.*?)\s*!}/',
            $this->compilesForeach() => '/@kurumiforeach\s*\((.*?)\)(.*?)\s*@endkurumiforeach/s',
            $this->compilesKurumiPhp() => '/@kurumiphp\s*(.*?)\s*@endkurumiphp/s',
            $this->compilesKurumiSection() => '/@kurumiSection\s*\((.*?)\)(.*?)\s*@endkurumisection/s',
            $this->compilesKurumiSingleSection() =>'/@kurumiSection\s*\((.*)\)\s*/',
            $this->compilesKurumiContent() => '/@kurumiContent\s*\((.*)\)\s*/',
            $this->compilesKurumiInclude() => '/@kurumiInclude\s*\((.*)\)\s*/',
            $this->compilesKurumiImport() => '/@kurumiImport\s*\((.*)\)\s*/',
            $this->compilesOppai() => '/@oppai\s*\((.*)\)\s*/'

        ] as $replacement => $pattern) {

            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }



    /**
     *  
     *  Compile directive kurumiExtends dipaling
     *  bawah dari sebuah content files.
     *
     *  @return void 
     **/
    protected function compilerKurumiExtends(): void
    {   
        $pattern = '/@kurumiExtends\s*\((.*)\)\s*/';
        preg_match($pattern, $this->content, $matches);

        if (isset($matches[1])) {
            $content = preg_replace(
                pattern: $pattern,
                replacement: $this->compilesKurumiExtends(@$matches[1]),
                subject: $this->content
            );

            $this->content = $this->addFooters($content);
        }
    }



    /**
     *
     *  Tambahkan footer kedalam content file.
     *
     *  @param string $content
     *  @return string 
     **/
    protected function addFooters(string $content): string
    {
        return $content . implode("\n", $this->footer);
    }



    /**
     *
     *  Set content file dan kembalikan 
     *  object ini.
     *
     *  @param string $path
     *  @return KurumiDirectiveInterface 
     **/
    public function files(string $path): KurumiCompiler
    {
        $this->content  = $this->getFileContent($path);
        $this->pathView = $path;
        return $this;
    }



    /**
     * 
     *  Dapatkan path input lengkap.
     *
     *  @return string
     **/
    public function getPathInput(): string
    {
        $path = $this->directoryInput . $this->pathView . parent::DEFAULT_FILE_EXTENSION;
        return $path;
    }



    /**
     * 
     *  Dapatkan path output lengkap.
     *
     *  @return string
     **/
    public function getPathOutput(): string
    {
        $path = $this->directoryOutput . pathToDot($this->pathView) . '.php';
        return $path;
    }



    /**
     * 
     *  Render, Generate file baru dengan hasil
     *  convert directive.
     *
     *  @param string $view 
     *  @return void 
     **/
    public function compile(): void
    {
        $this->validateDirectory();

        $this->compilerKurumiExtends();

        $pathOutput   = $this->getPathOutput();
        $pathInput    = $this->getPathInput();
        $finalContent = $this->toPhpValid($this->content);

        
        // saat pertamakali compile dijalankan,
        // selanjutnya compile akan dijalankan jika
        // terdapat perubahan difile input.
        if (!file_exists($pathOutput)) {
            file_put_contents($pathOutput, $finalContent); 
        } elseif (isFileUpdate($pathInput, $pathOutput)) {
            file_put_contents($pathOutput, $finalContent); 
        }
    }
}
