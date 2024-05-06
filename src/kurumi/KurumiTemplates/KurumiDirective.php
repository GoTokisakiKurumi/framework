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
    const DEFAULT_FOLDER_GENERATE = PATH_STORAGE_APP;


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
    protected array $directive = [];



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
        $this->addDefaultDirectives();
    }



    /**
     *  
     *  Handler load file yang akan digenerate.
     *  dan kembalikan hasilnya.
     *  
     *  @param string $path
     *  @return string 
     *  @throws \Exception jika file template tidak ada 
     **/
    private function loadTemplate(string $path): string
    {
        $templatePath = $this->basePath . $path . self::DEFAULT_FILE_EXTENSION;
        if(!file_exists($templatePath)) {
            throw new \Exception("Files template tidak ditemukan: $templatePath");
        }

        return file_get_contents($templatePath);
    }



    /**
     * 
     *  Menambahkan directive baru.
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
     *  Menambahkan directive baru dengan array.
     *
     *  @param array $directives
     *  @return void
     **/
    public function addDirectiveAll(array $directives): void
    {
        if(!is_null($directives) and sizeof($directives) > 0) {
            foreach($directives as $pattern => $replacement) {
                $this->directive[$pattern] = $replacement;
            }
        }
    }

    /**
     *  
     *  Tambahkan default directive 
     *  @return void 
     **/
    private function addDefaultDirectives(): void
    {

        $this->addDirectiveAll([
            '/{{\s*(.*?)\s*}}/' =>'<?php echo htmlspecialchars($1) ?>',
            '/{!\s*(.*?)\s*!}/' =>'<?php echo $1 ?>',
            '/@kurumiphp\s*(.*?)\s*@endkurumiphp/s' => '<?php $1 ?>',
            '/@kurumiExtends\s*\((.*)\)\s*/' => '<?php $template->extendContent($1) ?>',
            '/@kurumiSection\s*\((.*?)\)(.*?)\s*@endkurumisection/s' => '<?php $template->startContent($1) ?>$2<?php $template->stopContent(); ?>',
            '/@kurumiSection\s*\((.*)\)\s*/' =>'<?php $template->startContent($1) ?>',
            '/@kurumiContent\s*\((.*)\)\s*/' => '<?php $this->content($1) ?>',
            '/@kurumiInclude\s*\((.*)\)\s*/' =>'<?php $template->includeFile($1) ?>',
            '/^\s*[\r\n]+/m' => '',
            //'/[\r\n]+/' => ''
        ]);
    }



    /**
     * 
     *  Mengubah syntax directive menjadi syntax php biasa,
     *  dan kembalikan hasilnya.
     * 
     *  @param string $content 
     *  @return string 
     **/
    private function processesDirectives(string $content): string
    {
        foreach ($this->directive as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }



    /**
     * 
     *  Render, Generate file baru dengan hasil
     *  convert directive.
     *
     *  @param string $path 
     *  @return void 
     **/
    public function render(string $path): void
    {
        $templateContent = $this->loadTemplate($path);
        $resultContent   = $this->processesDirectives($templateContent);

        $destinationDirectory = self::DEFAULT_FOLDER_GENERATE;
        if (!file_exists($destinationDirectory)) {
            mkdir($destinationDirectory, 0777 , true);
        }

        file_put_contents(self::DEFAULT_FOLDER_GENERATE . pathToDot($path) . '.php', $resultContent);
    }
}
