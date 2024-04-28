<?php

namespace Kurumi\KurumiTemplates;

/**
 *
 *
 *  class KurumiTemplate 
 *
 *  untuk mengatur kebutuhan layouting.
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
class KurumiTemplate {

    /**
     *  
     *  menyimpan nama layout yang akan digunakan.
     *
     *  @property string $layout 
     *
     **/
    protected string $layout;

    /**
     *
     *  menyimpan file buffer.
     *
     *  @property string $buffer 
     *
     **/
    protected string $buffer;

    /**
     *
     *  @method layoutName()
     *
     **/
    public function layoutName(string $name): void
    {
        if ($this->layout === $name)
        {
            echo $this->buffer;
        }
    }

    /**
     *  
     *  @methods startContent() 
     *
     **/
    public function startContent(string $name)
    {
        $this->layout = $name;
        return ob_start();
    }

    /**
     *
     *  @method stopContent()
     *  
     **/
    public function stopContent(): void
    {
        $this->buffer = ob_get_clean();
    }

    /**
     *
     *  @method extendContent()
     *
     **/
    public function extendContent(string $path): void
    {
        include_once PATH_VIEWS . $path . '.kurumi.php';
    }
}
