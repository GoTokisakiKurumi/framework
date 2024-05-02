<?php

namespace Kurumi\Container;

/**
 *
 *  Class Container 
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
class Container implements ContainerInterface
{
    /**
     *  
     *  Menyimpan class yang dibindings 
     *
     *  @property array $bindings 
     *
     **/
    private array $bindings = [];

    /**
     *
     *  Binding class kedalam container
     *
     *  @param string $abstract 
     *  @param mixed $concrete 
     *  @return void 
     **/
    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /** 
     *
     *  Membuat instance class dari container
     *  
     *  @param string $abstract 
     *  @return mixed 
     **/
    public function make(string $abstract): mixed
    {
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            return $concrete($this);
        }
        throw new \Exception("Instance for '$abstract' is not registered.");
    }
}
