<?php


namespace Kurumi\Views\Compilers;


use Kurumi\FileSystems\FileSystem;
use Whoops\Exception\ErrorException;


/**
 *
 *  Abstarct compiler 
 *
 *  @author Lutfi Aulia Sidik 
 **/
abstract class Compiler {
    
    
    /**
     * 
     *  Menyimpan directory output untuk
     *  tempat menyimpan hasil compile.
     *
     *  @property string $directoryInput
     **/
    protected string $directoryOutput = "";


    /**
     * 
     *  @property-read Kurumi\FileSystems\FileSystem $files 
     **/
    public function __construct(
        protected readonly FileSystem $files
    ){}



    /**
     * 
     *  Memulai untuk mengcompile files.
     *
     *  @param string $path
     *  @param string $args
     *  @return void 
     **/
    abstract public function compile(string $path, string $args): void;



    /**
     * 
     *  Set directory output untuk tempat
     *  menyimpan file hasil compile.
     *
     *  @param string $directory
     *  @return mixed
     **/
    abstract public function setDirectoryOutput(string $directory);



    /**
     * 
     *  Dapatkan file content.
     *
     *  @param string $path
     *  @throws Whoops\Exception\ErrorException jika file tidak ditemukan
     *  @return string 
     **/
    protected function getFileContent(string $path): string 
    {
        if (!$this->files->exists($path)) {
            throw new ErrorException("File [$path] tidak ditemukan.");
        }

        return $this->files->get($path);
    }



    /**
     * 
     *  Dapatkan directory output
     *
     *  @return string
     **/
    public function getDirectoryOutput(): string
    {   
        return $this->directoryOutput;
    }
}
