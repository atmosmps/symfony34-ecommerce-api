<?php

namespace ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiBundle extends Bundle
{
    // Sobrescrevendo a function Boot que é executada na inicializacao do Bundle.
    public function boot()
    {
        putenv('PAGSEGURO_ENV=sandbox');
        putenv('PAGSEGURO_EMAIL=atmos.mps@gmail.com');
        putenv('PAGSEGURO_TOKEN_SANDBOX=19717EBDD4D54B8A80969F35FDD7C9B6');

        \PagSeguro\Library::initialize();
        \PagSeguro\Library::cmsVersion()->setName("Symfony34EcommerceApi")->setRelease("1.0.0");
        \PagSeguro\Library::moduleVersion()->setName("EcommerceApi")->setRelease("1.0.0");
    }
}
