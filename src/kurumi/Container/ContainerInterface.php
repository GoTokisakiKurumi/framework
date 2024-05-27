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


    /**
     * 
     *  Bind selain class kedalam container.
     * 
     *  @param string $abstract
     *  @param mixed $concrete
     *  @return void 
     **/
    public function shared(string $key, mixed $value): void;


    /**
     *
     *  Dapatkan bind atau shared.
     *
     *  @param string $abstract
     *  @throws \Exception jika bindings tidak tersedia
     *  @return mixed
     **/
    public function get(string $abstract, bool $isShared = true): mixed;


    /**
     *
     *  Cek apakah instance object atau shared
     *  terdaftar atau tidak. 
     *
     *  @param string $abstract 
     *  @param bool|false $isShared 
     *  @return bool 
     **/
    public function has(string $abstract, bool $isShared = false): bool;
}
