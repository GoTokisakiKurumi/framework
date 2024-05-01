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
     *  @param mixed $concrete 
     *  @return void 
     **/
    public function bind(string $abstract, mixed $concrete): void;

    /**
     *
     *  @param string $abstract
     *  @return mixed 
     **/
    public function make(string $abstract): mixed;
}
