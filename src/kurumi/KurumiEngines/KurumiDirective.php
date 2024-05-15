<?php

namespace Kurumi\KurumiEngines;



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
     *  @property string $pathOutput 
     *
     *  Menyimpan path input.
     **/
    protected readonly string $pathInput;


    /**
     * 
     *  @property string $pathOutput 
     *
     *  Menyimpan path Output.
     **/
    protected readonly string $pathOutput;


    /**
     *  
     *  Menyimpan $directive.
     *
     *  @property array $directive
     **/
    protected array $directive = [];



    /**
     *  
     *  Menginisialisasi property.
     *
     *  @param string $pathInput
     *  @param string $pathOutput 
     **/
    public function __construct(string $pathInput, string $pathOutput)
    {
        $this->pathInput = $pathInput;
        $this->pathOutput = $pathOutput;
        $this->addDefaultDirectives();
    }



    /**
     *  
     *  Handle load file yang akan digenerate dan 
     *  kembalikan hasilnya.
     *  
     *  @param string $path
     *  @return string 
     *  @throws \Exception jika file template tidak ada 
     **/
    protected function loadFile(string $path): string
    {
        $pathFile = $this->pathInput . $path . self::DEFAULT_FILE_EXTENSION;
        if(!file_exists($pathFile)) {
            throw new \Exception("Files tidak ditemukan: $pathFile");
        }

        return file_get_contents($pathFile);
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
            '/@kurumiforeach\s*\((.*?)\)(.*?)\s*@endkurumiforeach/s' => '<?php foreach($1): ?>$2<?php endforeach; ?>',
            '/@kurumiphp\s*(.*?)\s*@endkurumiphp/s' => '<?php $1 ?>',
            '/@kurumiExtends\s*\((.*)\)\s*/' => '<?php $template->extendContent($1) ?>',
            '/@kurumiSection\s*\((.*?)\)(.*?)\s*@endkurumisection/s' => '<?php $template->startContent($1) ?>$2<?php $template->stopContent(); ?>',
            '/@kurumiSection\s*\((.*)\)\s*/' =>'<?php $template->startContent($1) ?>',
            '/@kurumiContent\s*\((.*)\)\s*/' => '<?php $template->content($1) ?>',
            '/@kurumiInclude\s*\((.*)\)\s*/' =>'<?php $template->includeFile($1) ?>',
            '/@kurumiImport\s*\((.*)\)\s*/' =>'<?php $template->importFile($view, $1) ?>',
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
        $fileContent   = $this->loadFile($path);
        $resultContent = $this->processesDirectives($fileContent);

        $destinationDirectory = $this->pathOutput;
        if (!file_exists($destinationDirectory)) {
            mkdir($destinationDirectory, 0777 , true);
        }

        file_put_contents($destinationDirectory . pathToDot($path) . '.php', $resultContent);
    }
}
