<?php


namespace Kurumi\Views;


use ErrorException;


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
     *  Set path dan data
     *
     *  @param string $path
     *  @param array $data 
     **/
    public function __construct(string $path, array $data = [])
    {
        $this->path = $path;
        $this->data = $data;
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
            app('filesystem')->require($this->getPath(), $this->getData());
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
        if (!file_exists($path)) {
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
