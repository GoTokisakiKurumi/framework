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
final class KurumiCompiler extends KurumiEngine implements CompilerInterface
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
    private string $pathInput = "";


    /**
     *  
     *  Menyimpan directive yang akan 
     *  selalu dibawah.
     *
     *  @property array $footer
     **/
    private array $footer = [];



    /**
     *  
     *
     **/
    public function __construct(){}



    /**
     * 
     *  Compile kurumi template.
     *
     *  @param string $view 
     *  @return void 
     **/
    public function compile(string $path): void
    {

        if ($this->validatePathInput()) {

            $pathInput    = $this->getPathInput();
            $fileContent  = $this->getFileContent($pathInput);
            $finalContent = $this->toPhpValid($fileContent);
            $finalContent = $this->compilerKurumiExtends($finalContent);

            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }
        
            // saat pertamakali compile dijalankan,
            // selanjutnya compile akan dijalankan jika
            // terdapat perubahan difile input.
            if (!file_exists($path)) {
                file_put_contents($path, $finalContent); 
            } elseif (isFileUpdate($pathInput, $path)) {
                file_put_contents($path, $finalContent); 
            }
        }
    }


    
    /**
     *
     *  Validasi path input.
     *
     *  @throw Exception jika folder input tidak ditemukan.
     *  @return bool
     **/
    private function validatePathInput(): bool
    {
        if (!file_exists($this->pathInput) || !is_writable($this->pathInput)) {
            throw new Exception("Direktori input tidak valid: {$this->pathInput}");
        }

        return true;
    }



    /**
     * 
     *  Replace directive dengan php valid.
     *
     *  @param string $content
     *  @return string
     **/
    private function toPhpValid(string $content): string
    {
        foreach ([
            $this->compilesEchos() => '/{{\s*(.*?)\s*}}/',
            $this->compilesEcho() => '/{!\s*(.*?)\s*!}/',
            $this->compilesForeach() => '/@kurumiforeach\s*\((.*?)\)(.*?)\s*@endkurumiforeach/s',
            $this->compilesKurumiPhp() => '/@kurumiphp\s*(.*?)\s*@endkurumiphp/s',
            $this->compilesKurumiSection() => '/@kurumiSection\s*\((.*?)\)(.*?)\s*@endkurumisection/s',
            $this->compilesKurumiSingleSection() =>'/@kurumiSection\s*\((.*)\)\s*/',
            $this->compilesZafkiel() => '/@zafkiel\s*\((.*)\)\s*/',
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
     *  Compile directive zafkiel dipaling
     *  bawah dari sebuah content files.
     *  
     *  @param string $content
     *  @return string
     **/
    protected function compilerKurumiExtends(string $content)
    {   
        $pattern = '/@kurumiExtends\s*\((.*)\)\s*/';
        preg_match($pattern, $content, $matches);

        if (isset($matches[1])) {
            $content = preg_replace(
                pattern: $pattern,
                replacement: $this->compilesKurumiExtends($matches[1]),
                subject: $content
            );

            return $this->addFooters($content);                
        }

        return $content;
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
     *  Set path input.
     *
     *  @param string $path
     *  @return void 
     **/
    public function setPathInput(string $path): void
    {
        $this->pathInput = $path;
    }



    /**
     * 
     *  Dapatkan path input.
     *
     *  @return string
     **/
    public function getPathInput(): string
    {   
        $path = $this->pathInput;
        return $path;
    }
}
