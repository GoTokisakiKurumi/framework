<?php

use Kurumi\KurumiTemplates\KurumiTemplate;

/**
 *
 *
 *  view()
 *
 *  untuk menampilkan view.
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
function view(string $path, array $data = [])
{
    $pathFiles = PATH_VIEWS . $path;


    extract($data, EXTR_PREFIX_SAME, "kurumi");

    $template = new KurumiTemplate();
    
    #try {
        if (file_exists($pathFiles . '.kurumi.php')) {
            include_once $pathFiles . '.kurumi.php';
        } else {
            include_once $pathFiles . '.php';
        }
    #}  catch (Exception) {
        #throw new Error(ERROR_FILENAME);
    #}

}
