<?php


namespace Kurumi\Views;


use ErrorException;
use Kurumi\FileSystems\FileSystem;


/**
 *
 *  Class yang bertanggung jawab atas kebutuhan
 *  menampilkan file.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class View 
{


    /**
     *
     *  Menyimpan instance object FileSystem.
     *
     *  @property-read Kurumi\FileSystems\FileSystem $files
     **/
    protected readonly FileSystem $files;

    
    /**
     *
     *  Menyimpan path.
     *
     *  @property-read string $path
     **/
    protected readonly string $path;


    /**
     *
     *  Menyimpan data.
     *
     *  @property-read array $data 
     **/
    protected readonly array $data;



    /**
     *  
     *  Inisialisasi property.
     *  
     *  @param Kurumi\FileSystems\FileSystem $files
     *  @param string $path
     *  @param array $data 
     **/
    public function __construct(FileSystem $files, string $path, array $data = [])
    {
        $this->path  = $path;
        $this->data  = $data;
        $this->files = $files;
    }



    /**
     *
     *  Render file atau menampilkan file.
     *
     *  @param string $path
     *  @param array  $data
     *  @return void 
     **/
    public function render(): void
    {
        if ($this->validatePath($this->getPath())) {
            $this->files->require(
                path: $this->getPath(),
                data: $this->getData()
            );
        }   
    }



    /**
     *
     *  Validasi path.
     *
     *  @param string $path
     *  @throws ErrorException jika file tidak ditermukan
     *  @return bool 
     **/
    private function validatePath(string $path): bool
    {
        if (!$this->files->exists($path)) {
            throw new ErrorException("($path) jalur file tidak ditermukan.");
        }

        return true;
    }



    /**
     * 
     *  Dapatkan path.
     *
     *  @return string
     **/
    public function getPath(): string
    {
        return $this->path;
    }



    /**
     * 
     *  Dapatkan data.
     *
     *  @return array 
     **/
    public function getData(): array
    {
        return $this->data;
    }
}
