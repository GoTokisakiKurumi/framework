<?php

namespace Kurumi\Container;


/**
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
interface ContainerInterface {


    /**
     *
     *  @param string $abstract
     *  @param string|null $concrete 
     *  @return void 
     **/
    public function bind(string $abstract, $concrete = null): void;

 
    /**
     *
     *  @param string $abstract
     *  @return mixed 
     **/
    public function make(string $abstract): mixed;
}
