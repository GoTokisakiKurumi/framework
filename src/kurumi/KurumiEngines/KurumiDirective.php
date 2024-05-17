<?php

namespace Kurumi\KurumiEngines;


use Exception;


/**
 *
 *  Class KurumiDirective yang bertanggung jawab 
 *  atas semua hal yang mengenai directive.
 *
 *  @author Lutfi Aulia Sidik
 **/
final class KurumiDirective extends KurumiEngine implements KurumiEngineInterface
{


    /**
     *  
     *  Menyimpan $directive.
     *
     *  @property array $directive
     **/
    protected array $directive = [];



    /**
     *  
     *  Jalankan method yang perlu dijalankan saat 
     *  pertamakali class dipanggil.
     *
     *  @property-read string $pathInput menyimpan path input
     *  @property-read string $pathOutput menyimpan path output
     **/
    public function __construct(
        public readonly string $pathInput,
        public readonly string $pathOutput
    )
    {
        $this->addDefaultDirectives();
    }



    /**
     *
     *  Validasi folder input dan output jika 
     *  folder input tidak ditemukan maka buat
     *
     *  @return void 
     *  @throw \Exception jika folder input tidak ditemukan.
     **/
    private function validateDirectory(): void
    {
        if (!file_exists($this->pathInput) || !is_writable($this->pathInput)) {
            throw new Exception("Direktori input tidak valid: {$this->pathInput}");
        } elseif (@$this->pathOutput[-1] !== "/") {
            throw new Exception("Direktori output tidak valid: {$this->pathOutput}");
        } elseif (!file_exists($this->pathOutput)) {
            mkdir($this->pathOutput, 0777, true);
        }
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
    private function loadFile(string $path): string
    {
        $pathFile = $this->pathInput . $path . parent::DEFAULT_FILE_EXTENSION;
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
        $this->validateDirectory();

        $fileContent   = $this->loadFile($path);
        $resultContent = $this->processesDirectives($fileContent);

        file_put_contents($this->pathOutput . pathToDot($path) . '.php', $resultContent);
    }
}
