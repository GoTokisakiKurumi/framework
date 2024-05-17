<?php


/**
 *
 *
 *  @author Lutfi Aulia Sidik 
 **/
namespace Kurumi\KurumiEngines;



/**
 *
 *  interface KurumiTemplateInterface 
 *
 *  @author Lutfi Aulia Sidik
 *
 **/
interface KurumiTemplateInterface {


    public function content(string $name): void;


    public function startContent(string $name, string $value = ''): string;


    public function stopContent(): void;


    public function includeFile(string $path, array $data = []): void;


    public function extendContent(string $path): void;


    public function importFile(string $view, string $path, string $key = null): void;


    public function render(string $path, array $data = []): void;

}


