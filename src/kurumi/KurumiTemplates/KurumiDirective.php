<?php

namespace Kurumi\KurumiTemplates;



/**
 *
 *  Class KurumiDirective
 *
 *  @author Lutfi Aulia Sidik
 **/
class KurumiDirective implements KurumiDirectiveInterface {

    /**
     * 
     *  Default file extension.
     **/
    const DEFAULT_FILE_EXTENSION = '.kurumi.php';


    /**
     *
     *  Default path folder generate
     **/
    const DEFAULT_FOLDER_GENERATE = PATH_STORAGE . 'app/';


    /**
     * 
     *  @property string $basePath 
     *
     *  Menyimpan base path.
     **/
    protected readonly string $basePath;


    /**
     *  
     *  Menyimpan $directive.
     *
     *  @property array $directive
     **/
    protected array $directive;


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
        $this->directive = [];
        $this->addDefaultDirectives();
    }


    /**
     *  
     *  Load Template.
     *  
     *  @param string $path
     *  @return string 
     *  @throws \Exception jika file template tidak ada 
     **/
    public function loadTemplate(string $path): string
    {
        $templatePath = $this->basePath . $path . self::DEFAULT_FILE_EXTENSION;
        if(!file_exists($templatePath)) {
            throw new \Exception("Files template tidak ditemukan: $templatePath");
        }

        return file_get_contents($templatePath);
    }


    /**
     * 
     *  Menambahkan directive.
     *
     *  @param string $pattern
     *  @param string $replacement
     *  @return void 
     **/
    public function addDirective(string $pattern, string $replacement): void
    {
        $this->directive[$pattern] = $replacement;
    }

    /**
     *  
     *  Tambahkan default directive 
     *  
     *  @return void 
     **/
    protected function addDefaultDirectives(): void
    {
        $this->addDirective('/{{\s*(.*?)\s*}}/', '<?php echo htmlspecialchars($1) ?>');
        $this->addDirective('/{!\s*(.*?)\s*!}/', '<?php echo $1 ?>');
        $this->addDirective('/{\s*(.*?)\s*}/', '<?php $1 ?>');
        $this->addDirective('/@kurumiExtends\s*\((.*)\)\s*/', '<?php $template->extendContent($1) ?>');
        $this->addDirective('/@kurumiSection\s*\((.*)\)\s*/', '<?php $template->startContent($1) ?>');
        $this->addDirective('/@endKurumiSection/', '<?php $template->stopContent() ?>');
        $this->addDirective('/@kurumiContent\s*\((.*)\)\s*/', '<?php $this->content($1) ?>');
        $this->addDirective('/^\s*[\r\n]+/m', '');
        $this->addDirective('/[\r\n]+/', '');
    }


    /**
     * 
     *  Memproses directive.
     * 
     *  @param string $content 
     *  @return string 
     **/
    protected function processedDirectives(string $content): string
    {
        foreach ($this->directive as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }


    /**
     * 
     *  Render file.
     *
     *  @param string $path 
     *  @return void 
     **/
    public function render(string $path): void
    {
        $templateContent = $this->loadTemplate($path);
        $resultContent   = $this->processedDirectives($templateContent);

        $destinationDirectory = dirname(self::DEFAULT_FOLDER_GENERATE . $path);
        if (!file_exists($destinationDirectory)) {
            mkdir($destinationDirectory, 0777 , true);
        }            

        file_put_contents(self::DEFAULT_FOLDER_GENERATE . $path . '.php', $resultContent);
            
    }
}
