<?php


namespace Kurumi\Debug;


use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;


/**
 *
 *
 *  Class yang bertanggung jawab atas 
 *  Custom debug symfony.
 *  
 *  @author Lutfi Aulia Sidik 
 **/
class CustomDumper
{

    
    /**
     * 
     *  Menyimpan instace object HtmlDumper.
     *
     *  @property-read HtmlDumper $dumper
     **/
    protected readonly HtmlDumper $dumper;


    /**
     * 
     *  Menyimpan instace object VarCloner.
     *
     *  @property-read VarCloner $cloner
     **/
    protected readonly VarCloner $cloner;



    /**
     *
     *  isnstace object HtmlDumper.
     *
     *  @return void 
     **/
    public function __construct()
    {
        $this->dumper = new HtmlDumper();
        $this->cloner = new VarCloner();
    }



    /**
     *  
     *  Mulai melakukan debug.
     *
     *  @param mixed $var
     *  @return void 
     **/
    public function dump($var): void 
    {
        ob_start();
        $this->appendCss();
        $this->dumper->dump($this->cloner->cloneVar($var));
        $this->appendHtml(ob_get_clean());
    }


    
    /**
     *
     *  Tambahkan css custom.
     *
     *  @return void 
     **/
    private function appendCss(): void 
    {
        $this->dumper->setStyles([
            'default' => 'background-color:#141E27; color:#FFF; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
            'num' => 'color:#ae81ff',
            'const' => 'color:#66d9ef',
            'str' => 'color:#FFFFFF',
            'cchr' => 'color:#e6db74',
            'ref' => 'color:#a6e22e',
            'public' => 'color:#f92672',
            'protected' => 'color:#fd971f',
            'private' => 'color:#f8f8f2',
            'meta' => 'color:#75715e',
            'key' => 'color:#FFBB68',
            'index' => 'color:#FFF',
            'note' => 'color:#FF266D'
        ]);
    }


    
    /**
     *
     *  Tambahkan structure HTML.
     *
     *  @return void 
     **/
    private function appendHtml(string $output): void
    {
        echo '<!DOCTYPE html><html lang="en"><head><link href="css/app.css" rel="stylesheet"><title>Kurumi Debug</title><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>';
        echo "<body><div style='margin: 15px'>$output</div></body></html>";
    }
}

