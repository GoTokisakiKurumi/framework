<?php

namespace Kurumi\Consoles;

/**
 *
 *
 *
 *  @author Lutfi Aulia Sidik 
 **/
class Command {


    
    /**
     *
     *  @param array $argv 
     *  @return void 
     **/
    public function handleCommand($argv): void
    {  
        if (isset($argv["kawaii"]) || isset($argv["p"])) {
            $this->serve($argv["p"] ?? 3000);
        } elseif (isset($argv["h"])) {
            $this->help();
        } else {
            $this->help();
        }
    }



    /**
     *
     *  Jalanlan built-in web server.
     *
     *  @param int $port 
     *  @return void 
     **/
    protected function serve(int $port): void
    {
        $port = ($port === 1) ? 3000 : $port;
        exec("php -S localhost:{$port} -t public/");
    }

    protected function help(): void
    {
        echo "
 _  ___   _ ____  _   _ __  __ ___ 
| |/ / | | |  _ \| | | |  \/  |_ _|
| ' /| | | | |_) | | | | |\/| || | 
| . \| |_| |  _ <| |_| | |  | || | 
|_|\_\\\___/|_| \_\\\___/|_|  |_|___|
    (Sebuah mini framework >_<)

Usage:
    
    kawaii [option]  =>  jalankan web server
                         port default 3000
Options:
    
    -h               =>  tampilkan display help
    -p  [kawaii]     =>  atur port web server 
                         contoh: kawaii -p 8080        
                    
\n";
    }
}
