<?php


namespace Kurumi\FileSystems;

use Whoops\Exception\ErrorException;

/**
 *
 *  Class yang bertanggung jawab atas 
 *  file system.
 *
 *  @author Lutfi Aulia Sidik 
 **/
class FileSystem {



    /**
     *
     *  Cek apakah sebuah file exits.
     *
     *  @param string $path
     *  @return bool 
     **/
    public function isFile(string $path): bool
    {
        return file_exists($path);
    }



    /**
     * 
     *  Cek apakah sebuah file ada perubahan
     *  berdasarkan waktu terakhir diupdate. 
     *
     *  @param string $input
     *  @param string $output
     *  @return bool 
     **/
    public function isFileUpdate(string $input, string $output)
    {
        if ($this->isFile($input)) {
            $fileTimeInput  = filemtime($input);
            $fileTimeOutput = @filemtime($output) ?? 0;

            return $fileTimeInput >= $fileTimeOutput;
        }

        throw new ErrorException("File tidak ditemukan ($input).");
    }



    /**
     * 
     *  Require  
     *
     *  @param string $path 
     *  @param array $data 
     **/
    public function require(string $path, array $data = [])
    {
        if ($this->isFile($path)) { 
            $__path = $path;
            $__data = $data;

            return ( static function() use ($__path, $__data) {
                extract($__data, EXTR_SKIP);
                
                require $__path;
            })();
        }

        throw new ErrorException("File tidak ditemukan ($path).");
    }



    /**
     * 
     *  Require once 
     *
     *  @param string $path 
     *  @param array $data 
     **/
    public function requireOnce(string $path, array $data = [])
    {
        if ($this->isFile($path)) { 
            $__path = $path;
            $__data = $data;

            return ( static function() use ($__path, $__data) {
                extract($__data, EXTR_SKIP);
                
                require_once $__path;
            })();
        }

        throw new ErrorException("File tidak ditemukan ($path).");
    }
}