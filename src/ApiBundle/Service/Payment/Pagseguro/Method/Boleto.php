<?php
namespace ApiBundle\Service\Payment\Pagseguro\Method;


class Boleto extends Method
{
    private $credentials;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    public function proccess()
    {
        //Instantiate a new Boleto Object
        $boleto = new \PagSeguro\Domains\Requests\DirectPayment\Boleto();

        // Set the Payment Mode for this payment request
        $boleto->setMode('DEFAULT');

        /**
         * @todo Change the receiver Email
         */
        $boleto->setReceiverEmail('atmos.mps@gmail.com');

        // Set the currency
        $boleto->setCurrency("BRL");

        foreach (unserialize($this->order->getItems()) as $i) {
            // Add an item for this payment request
            $boleto->addItems()->withParameters(
                $i['id'],
                $i['name'],
                1,
                $i['price']
            );
        }

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $boleto->setReference("NL - " . $this->order->id); // NL - nexonlab

        //set extra amount
        // $boleto->setExtraAmount(11.5);

        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $userName = $this->order->getUser()->getFirstName() . ' - ' . $this->order->getUser()->getLastName();
        $boleto->setSender()->setName($userName);
        $boleto->setSender()->setEmail('email@sandbox.pagseguro.com.br'); // em ambiente de sandbox eu preciso obrigatoriamente passar este email
        $boleto->setSender()->setPhone()->withParameters(
            11,
            56273440
        );

        $boleto->setSender()->setDocument()->withParameters(
            'CPF',
            'insira um numero de CPF valido'
        );

        $boleto->setSender()->setHash($this->hashUser);

        $boleto->setSender()->setIp('127.0.0.0');

        // Set shipping information for this payment request
        $boleto->setShipping()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        // If your payment request don't need shipping information use:
        // $boleto->setShipping()->setAddressRequired()->withParameters('FALSE');

        //Get the crendentials and register the boleto payment
        $result = $boleto->register(
            $this->credentials
        );

        return $result;
    }
}
