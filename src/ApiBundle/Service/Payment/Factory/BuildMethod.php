<?php
namespace ApiBundle\Service\Payment\Factory;


use ApiBundle\Service\Payment\Pagseguro\Method\Boleto;
use ApiBundle\Service\Payment\Pagseguro\Method\CreditCard;

class BuildMethod
{
    // Quando eu determino o construtor de uma classe como private, eu não posso instanciar esta classe.
    private function __construct() {}

    public static function build($method, $credentials)
    {
        switch ($method) {
            case $method == 'BOLETO':
                return new Boleto($credentials);
            break;

            case $method == 'CREDITCARD':
                return new CreditCard($credentials);
            break;
        }
    }
}
