<?php


/**
 *  
 *  Ubah path string ke . 
 *  home/index to home.index 
 *  
 *  @author Lutfi Aulia Sidik 
 *  @param string $path 
 *  @param bool $reverse 
 *  @return void 
 **/
function pathToDot(string $path, bool $reverse = false): string
{
    return ($reverse) ? str_replace('.', '/', $path) : str_replace('/', '.', $path);
}
