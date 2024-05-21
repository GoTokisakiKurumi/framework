<?php

namespace Kurumi\Container;


use ReflectionClass;
use Exception;


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
     *  @throws \Exception Jika instance object sudah terdaftar.
     *  @return void 
     **/
    public function bind(string $abstract, $concrete = null): void
    {
        if (array_key_exists($abstract, $this->bindings)) {
            throw new Exception("Instance object ($abstract) sudah terdaftar.");
        }

        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        $this->bindings[$abstract] = $concrete;
    }



    /** 
     *
     *  Membuat instance dari class yang didaftarkan.
     *  
     *  @param string $abstract 
     *  @throws \Exception Jika instance object tidak ada.
     *  @return mixed 
     **/
    public function make(string $abstract): mixed
    {
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            return $this->resolve($concrete);
        }

        throw new Exception("Instance object '$abstract' tidak terdaftar.");
    }



    /**
     *  
     *  Jika terdapat dependency dimethod constructure 
     *  maka buat secara otomatis.
     *  
     *  @param string $concrete
     *  @throws \Exception Jika dependency bukan object.
     *  @return object 
     **/
    protected function resolve(string $concrete): object
    {
        $reflection = new ReflectionClass($concrete);

        $constructure = $reflection->getConstructor();
        
        if(is_null($constructure)) {
            return new $concrete;
        }

        $getDependencies = $constructure->getParameters();

        $instances = [];      
        foreach ($getDependencies as $dependency) {
            $dependencyClass = $dependency->getType();
            if ($dependencyClass->isBuiltin()) {
                throw new Exception("Tidak dapat menyelesaikan dependency class: {$dependency->getName()}");
            }
            
            $instances[] = $this->make($dependencyClass->getName());
        }
        
        return $reflection->newInstanceArgs($instances);
    }
}
