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
     *  Render file atau menampilkan file.
     *
     *  @param string $path
     *  @param array  $data
     *  @return void 
     **/
    public function render(string $path, array $data = [])
    {
        if ($this->validatePath($path)) {
            app('filesystem')->require($path, $data);
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
}
