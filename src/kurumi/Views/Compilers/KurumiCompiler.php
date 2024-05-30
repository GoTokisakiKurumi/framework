<?php

namespace Kurumi\Views\Compilers;


use Whoops\Exception\ErrorException;


/**
 *
 *  Class KurumiCompiler yang bertanggung jawab 
 *  atas semua hal yang mengenai compiler.
 *
 *  @author Lutfi Aulia Sidik
 **/
final class KurumiCompiler extends Compiler implements CompilerInterface
{

    use Traits\CompilesLayouts,
        Traits\CompilesIncludes,
        Traits\CompilesRawPhp,
        Traits\CompilesLoops,
        Traits\CompilesFunctions,
        Traits\CompilesEchos;


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
     *  Extension file yang didukung.
     *
     *  @property array $extension
     **/   
    private array $extension = ["kurumi" => ".kurumi.php"];



    /**
     * 
     *  Compile directive kurumi menjadi php
     *  yang valid.
     *
     *  @param string $path
     *  @param string $filename
     *  @throws Whoops\Exception\ErrorException jika file tidak ditemukan
     *  @return void 
     **/
    public function compile(string $path, string $filename): void
    {
        if ($this->files->exists($path)) {
            if (str_ends_with($path, $this->extension["kurumi"])) {
                $finalContent = $this->toPhpValid($this->getFileContent($path));
                $finalContent = $this->compilerKurumiExtends($finalContent);
            
                $this->createDirectoryOutput();

                $pathOutput = $this->createPathOutput($filename);
                if (!$this->files->exists($pathOutput)) {
                    $this->files->put($pathOutput, $finalContent);
                } elseif (isFileUpdate($path, $pathOutput)) {
                    $this->files->put($pathOutput, $finalContent); 
                }
            } else {
                throw new ErrorException("Extension selain [kurumi.php] tidak didukung.");
            }
        } else {
            throw new ErrorException("($path) File tidak ditemukan.");
        }
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
     *  Set directory output untuk tempat
     *  menyimpan file hasil compile.
     *
     *  @param string $directory
     *  @return Kurumi\Views\Compilers\CompilerInterface 
     **/
    public function setDirectoryOutput(string $directory): CompilerInterface
    {
        $this->directoryOutput = $directory;
        return $this;
    }



    /**
     * 
     *  Membuat directory output jika directory output
     *  tidak ditemukan.
     *
     *  @return bool 
     **/
    protected function createDirectoryOutput(): bool
    {
        $outputDir = $this->getDirectoryOutput();
        if ($this->files->exists($outputDir)) {
            return false;
        }

        return $this->files->makeDir($outputDir);
    }



    /**
     *   
     *  Membuat path output untuk menyimpan
     *  file hasil compile.
     *
     *  @param string $filename
     *  @return string 
     **/
    protected function createPathOutput(string $filename): string
    {
        $filename = str_replace('/', '.', $filename);
        $path = $this->getDirectoryOutput() . "/" . $filename . ".php";

        return $path;
    }
}
