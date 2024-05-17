<?php


namespace Kurumi\KurumiEngines;


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


    abstract public function render(string $path): void;
}
