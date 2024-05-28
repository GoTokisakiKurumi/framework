<?php


namespace Kurumi\Views\Traits;


/**
 *
 *  Trait yang bertanggung jawab atas 
 *  Layouting
 *  
 *  @author Lutfi Aulia Sidik 
 **/
trait ManagesLayouts {


    /**
     *
     *  Menyimpan final sections. 
     *  
     *  @property array $section 
     **/
    public array $sections = [];


    /**
     *
     *  Store dari prosess sections.
     *
     *  @property array $sectionStores
     **/
    public array $sectionStores = [];


    
    /**
     * 
     *  Mulai mengambil content.
     *
     *  @param string $section
     *  @param string|null $content 
     *  @return void 
     **/
    public function startSection(string $section, string $content = null): void
    {
        if (!is_null($content)) {
            $this->sections[$section] = $content;
        }elseif (ob_start()) {
            $this->sectionStores[] = $section;
        }
    }


    
    /**
     *  
     *  Berhenti mengambil content.
     *
     *  @return void 
     **/
    public function stopSection(): void
    {
        $section = array_pop($this->sectionStores);

        $this->sections[$section] = ob_get_clean();
    }


    
    /**
     *  
     *  Dapatkan string section dari content.
     *
     *  @param string $section 
     *  @return string 
     **/
    public function zafkielContent(string $section): string
    {
        if (isset($this->sections[$section])) {
            return $this->sections[$section];
        } else {
            return '';
        }
    }
}
