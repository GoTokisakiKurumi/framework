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
     *  Default file extension.
     *
     **/
    const DEFAULT_FILE_EXTENSION = '.kurumi.php';

    
    /**
     *  
     *  @property $transform 
     *
     *  Menyimpan object dari class KurumiTransform.
     *
     **/
    protected KurumiTransformInterface $transform;
        
   
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
    protected static array $buffer;

    /**
     *
     *  menyimpan key.
     *
     *  @property array $key 
     *
     **/
    protected static string $key; 


    /**
     *
     *  @method __construct()
     *
     *  inisialisasi property.
     *
     **/
    public function __construct(KurumiTransformInterface $transform)
    {
        $this->transform = $transform;
        self::$content = [];
        self::$buffer = [];
        self::$key = ""; 
    }
    


    /**
     *
     *  @method content()
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
            $this->transform->render($path);
            include PATH_STORAGE . 'app/' . $path . '.php';
        } catch (\Exception) {
            throw new \Exception("Kurumi: Tampaknya file ($path) tidak dapat ditemukan. Seperti hatiku yang kehilangan dia :)");
        }
    }
}
