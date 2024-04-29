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
     *  @property array $content 
     *
     **/
    protected static array $content;

    /**
     *
     *  menyimpan file buffer.
     *
     *  @property array $buffer 
     *
     **/
    protected static array $buffer = [];

    /**
     *
     *  menyimpan key.
     *
     *  @property array $buffer 
     *
     **/
    protected static string $key; 

    /**
     *
     *  @method layoutName()
     *
     **/
    public function content(string $name): void
    {
        if (array_key_exists($name, self::$content)) {
            if (self::$content[$name] === $name) {
                echo self::$buffer[$name];
            }
        }
    }

    /**
     *  
     *  @methods startContent() 
     *
     **/
    public function startContent(string $name)
    {
        self::$content[$name] = $name;
        self::$key = $name;

        return ob_start();
    }

    /**
     *
     *  @method stopContent()
     *  
     **/
    public function stopContent(): void
    {
        self::$buffer[self::$key] = ob_get_clean();
    }

    /**
     *
     *  @method extendContent()
     *
     **/
    public function extendContent(string $path): void
    {
        try {
            include_once PATH_VIEWS . $path . '.kurumi.php';
        } catch (\Exception) {
            throw new \Exception("Kurumi: Tampaknya file ($path) tidak dapat ditemukan. Seperti hatiku yang kehilangan iramanya :)");
        }
    }
}
