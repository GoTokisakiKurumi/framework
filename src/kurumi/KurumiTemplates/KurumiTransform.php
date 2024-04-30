<?php

namespace Kurumi\KurumiTemplates;



/**
 *
 *  Class KurumiTransform 
 *
 *  @author Lutfi Aulia Sidik
 *
 **/
class KurumiTransform implements KurumiTransformInterface {

    /**
     * 
     *  Default file extension.
     *
     **/
    const DEFAULT_FILE_EXTENSION = '.kurumi.php';


    /**
     *
     *  Default path folder generate
     *
     **/
    const DEFAULT_FOLDER_GENERATE = PATH_STORAGE . 'app/';


    /**
     * 
     *  @property string $basePath 
     *
     *  Menyimpan base path.
     *
     **/
    protected string $basePath;


    /**
     *
     *  @method __construct()
     *  
     *  Menginisialisasi property.
     *
     *  @param string $basePath
     **/
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }


    /**
     * 
     *  @method render()
     **/
    public function render(string $path): void
    {
        $isPath = $this->basePath . $path . self::DEFAULT_FILE_EXTENSION;
        $destinationDirectory = dirname(self::DEFAULT_FOLDER_GENERATE . $path);

        if (file_exists($isPath))
        {

            $replace = preg_replace('/{{\s*(.*?)\s*}}/', '<?php echo htmlspecialchars($1) ?>', file_get_contents($isPath));
            $replace = preg_replace('/{!\s*(.*?)\s*!}/', '<?php echo $1 ?>', $replace);
            $replace = preg_replace('/{\s*(.*?)\s*}/', '<?php $1 ?>', $replace);
            $replace = preg_replace('/@kurumiExtends\s*\((.*)\)\s*/', '<?php $template->extendContent($1) ?>', $replace);
            $replace = preg_replace('/@kurumiSection\s*\((.*)\)\s*/', '<?php $template->startContent($1) ?>', $replace);
            $replace = preg_replace('/@endKurumiSection/', '<?php $template->stopContent() ?>', $replace);
            $replace = preg_replace('/@kurumiContent\s*\((.*)\)\s*/', '<?php $this->content($1) ?>', $replace);
            $replace = preg_replace('/^\s*[\r\n]+/m', '', $replace);
            $replace = preg_replace('/[\r\n]+/', '', $replace);


            if (!file_exists($destinationDirectory)) {
                mkdir($destinationDirectory, 0777 , true);
            }            

            file_put_contents(self::DEFAULT_FOLDER_GENERATE . $path . '.php', $replace);
            
        }
    }
}
