<?php

namespace Kurumi\Container;


use ReflectionClass;
use Exception;


/**
 *
 *  Class yang bertanggung jawab atas 
 *  IoC Container 
 *
 *  @author Lutfi Aulia Sidik 
 **/
class Container implements ContainerInterface
{

    /**
     * 
     *  Menyimpan instance object container.
     *
     *  @property ContainerInterface $instance
     **/
    private static ?ContainerInterface $instance = null;


    /**
     *  
     *  Menyimpan class yang dibindings.
     *
     *  @property array $bindings 
     *
     **/
    private array $bindings = [];
    


    /**
     *  
     *  Mencegah object diinstance secara
     *  langsung.
     **/
    private function __construct(){}
    


    /**
     *
     *  Binding class kedalam container.
     *
     *  @param string $abstract 
     *  @param mixed $concrete 
     *  @throws \Exception Jika instance object sudah terdaftar.
     *  @return void 
     **/
    public function bind(string $abstract, $concrete = null): void
    {
        if (is_null($concrete)) $concrete = $abstract;
        if (array_key_exists($abstract, $this->bindings)) {
            throw new Exception("Instance object ($abstract) sudah terdaftar.");
        }

        $this->bindings[$abstract] = $concrete;
    }



    /** 
     *
     *  Membuat instance object dari class yang didaftarkan.
     *  
     *  @param string $abstract 
     *  @throws \Exception Jika instance object tidak ada.
     *  @return mixed 
     **/
    public function make(string $abstract): mixed
    {
        if (!$this->has($abstract)) {
            throw new Exception("Instance object '$abstract' tidak terdaftar.");
        }

        $concrete = $this->bindings[$abstract];
        return $this->resolve($concrete);
    }



    /**
     *
     *  Mengecek apakah instance object terdaftar
     *  atau tidak. 
     *
     *  @param string $abstract 
     *  @return void 
     **/
    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
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



    /**
     *
     *  (singleton)
     *
     *  Mendapatkan instance object secara langsung 
     *  dan memastikan object diinstance satu kali.
     *
     *  @return Container 
     **/
    public static function getInstance(): ContainerInterface
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}
