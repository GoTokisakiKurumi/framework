<?php


namespace Kurumi\Container;


use ReflectionClass;
use ReflectionParameter;
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
     **/
    private array $bindings = [];


    /**
     * 
     *  Menyimpan data yang dishared.
     *
     *  @property array $shared 
     **/
    protected array $shared = [];



    /**
     *  
     *  Mencegah object diinstance secara
     *  langsung.
     **/
    private function __construct() {}



    /**
     *
     *  Bindings class kedalam container.
     *
     *  @param string $abstract 
     *  @param mixed $concrete 
     *  @throws \Exception Jika instance object sudah terdaftar.
     *  @return void 
     **/
    public function bind(string $abstract, $concrete = null): void
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
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
     *  Bind selain class kedalam container.
     * 
     *  @param string $abstract
     *  @param mixed $concrete
     *  @return void 
     **/
    public function shared(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }



    /**
     *
     *  Dapatkan bind atau shared.
     *
     *  @param string $abstract
     *  @throws \Exception jika bindings tidak tersedia
     *  @return mixed
     **/
    public function get(string $abstract, bool $isShared = true): mixed
    {
        if ($isShared === true && $this->has($abstract, true)) {
            return $this->shared[$abstract];
        } elseif ($isShared === false && $this->has($abstract)) {
            return $this->bindings[$abstract];
        } else {
            throw new Exception("($abstract) tidak tersedia didalam container.");
        }
    }



    /**
     *
     *  Cek apakah instance object atau shared
     *  terdaftar atau tidak. 
     *
     *  @param string $abstract 
     *  @param bool|false $isShared 
     *  @return bool 
     **/
    public function has(string $abstract, bool $isShared = false): bool
    {
        if (!$isShared) {
            return isset($this->bindings[$abstract]);
        } else {
            return isset($this->shared[$abstract]);
        }
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

        $constructor = $reflection->getConstructor();
        
        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instances = $this->resolveDependencies($dependencies);

        return $reflection->newInstanceArgs($instances);
    }



    /**
     *  
     *  Resolusi dependencies dari parameter constructor.
     *  
     *  @param ReflectionParameter[] $dependencies
     *  @throws \Exception Jika dependency bukan object.
     *  @return array 
     **/
    protected function resolveDependencies(array $dependencies): array
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            $dependencyClass = $dependency->getType();
            
            if ($dependencyClass === null || $dependencyClass->isBuiltin()) {
                throw new Exception("Tidak dapat menyelesaikan dependency class: {$dependency->getName()}");
            }
            
            $dependencyName = $dependencyClass->getName();

            if ($this->has($dependencyName)) {
                $results[] = $this->make($dependencyName);
            } else {
                $results[] = $this->resolve($dependencyName);
            }
        }

        return $results;
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
