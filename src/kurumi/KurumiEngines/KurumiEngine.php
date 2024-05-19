<?php


namespace Kurumi\KurumiEngines;


use Exception;


/**
 *
 *  Abstract class KurumiEngine 
 *  @author Lutfi Aulia Sidik
 **/
abstract class KurumiEngine
{


    /**
     *
     *  Default file extension. 
     **/
    const DEFAULT_FILE_EXTENSION = ".kurumi.php";



    /**
     *  
     *  Mengambil content file.
     *
     *  @param string $path 
     *  @throws \Exception jika file tidak ditemukan 
     *  @return string  
     **/
    protected function getFileContent(string $path): string
    {
        if (!file_exists($path)) {
            throw new Exception("($path) file tidak ditemukan.");
        }

        return file_get_contents($path);
    }
}
