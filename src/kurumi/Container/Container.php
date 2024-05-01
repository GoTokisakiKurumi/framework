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
     *  @property array $bindings 
     *
     **/
    private array $bindings = [];

    /**
     *
     *  Binding class kedalam container
     *
     **/
    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /** 
     *
     * Membuat instance class dari container
     *
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
