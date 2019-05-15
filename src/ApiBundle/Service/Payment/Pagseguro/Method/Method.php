<?php
namespace ApiBundle\Service\Payment\Pagseguro\Method;

abstract class Method
{
    public $tokenCard;

    public $installments; // parcelas

    public $hashUser;

    public $order;

    abstract public function proccess();
}
