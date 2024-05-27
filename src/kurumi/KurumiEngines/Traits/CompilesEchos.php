<?php


namespace Kurumi\KurumiEngines\Traits;


/**
 *
 *  Trait yang bertanggung jawab atas 
 *  compiles echo.
 *
 *  @author Lutfi Aulia Sidik 
 **/
trait compilesEchos {


    /**
     *
     *  compiles {{ }} menjadi php yang valid.
     *
     **/ 
    protected function compilesEchos(): string
    {
        return '<?php echo htmlspecialchars($1) ?>';
    }



    /**
     *
     *  compiles {! !} menjadi php yang valid.
     *
     **/ 
    protected function compilesEcho(): string
    {
        return '<?php echo $1 ?>';
    }

}

