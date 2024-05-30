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
    public function exists(string $path): bool
    {
        return file_exists($path);
    }



    /**
     *
     *  Membuat dan menulis file.
     *  (akan menimpa isi contents sebelumnya)
     *
     *  @param string $path
     *  @param mixed  $contents
     *  @param int    $flags
     *  @return void 
     **/
    public function put(string $path, mixed $contents = '', int $flags = 0): void
    {
        file_put_contents($path, $contents, $flags);
    }



    /**
     *  
     *  Dapatkan contents sebuah file.
     *  
     *  @param string $path 
     *  @throws ErrorException jika directory tidak valid 
     *  @return string|bool
     **/
    public function get(string $path): string|bool
    {
        if ($this->exists($path)) {
            return file_get_contents($path);
        }

        throw new ErrorException("File tidak ditemukan ($path).");
    }



    /**
     *  
     *  Buat folder.
     *
     *  @param string $path
     *  @param int    $permissions
     *  @param bool   $recursive
     *  @return bool
     **/
    public function makeDir(string $path, int $permissions = 0777, bool $recursive = true): bool
    {
        return mkdir($path, $permissions, $recursive);
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
        if ($this->exists($input)) {
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
        if ($this->exists($path)) { 
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
        if ($this->exists($path)) { 
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
