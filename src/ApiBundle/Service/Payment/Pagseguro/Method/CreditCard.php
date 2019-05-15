<?php
namespace ApiBundle\Service\Payment\Pagseguro\Method;


class CreditCard extends Method
{
    private $credentials;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    public function proccess()
    {
        //Instantiate a new direct payment request, using Credit Card
        $creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();

        /**
         * @todo Change the receiver Email
         */
        $creditCard->setReceiverEmail('atmos.mps@gmail.com');

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $creditCard->setReference("NL - " . $this->order->getId());

        // Set the currency
        $creditCard->setCurrency("BRL");

        foreach (unserialize($this->order->getItems()) as $i) {
            // Add an item for this payment request
            $creditCard->addItems()->withParameters(
                $i['id'],
                $i['name'],
                1,
                $i['price']
            );
        }

        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $userName = $this->order->getUser()->getFirstName() . ' - ' . $this->order->getUser()->getLastName();
        $creditCard->setSender()->setName($userName);
        $creditCard->setSender()->setEmail('c22983573204423475097@sandbox.pagseguro.com.br'); // em ambiente de sandbox eu preciso obrigatoriamente passar este email, mas em producao este é o email do usuario comprador.
        $creditCard->setSender()->setPhone()->withParameters(
            11,
            56273440
        );

        $creditCard->setSender()->setDocument()->withParameters(
            'CPF',
            '79507445021'
        );

        $creditCard->setSender()->setHash($this->hashUser);

        $creditCard->setSender()->setIp('127.0.0.0');

        // Set shipping information for this payment request // endereco de entrega
        $creditCard->setShipping()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        // Set billing information for credit card
        $creditCard->setBilling()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        // Set credit card token
        $creditCard->setToken($this->tokenCard);

        // Set the installment quantity and value (could be obtained using the Installments
        // service, that have an example here in \public\getInstallments.php)
        $installment = explode('|', $this->installments);
        $creditCard->setInstallment()->withParameters($installment[0], $installment[1], 12);

        // Set the credit card holder information
        $creditCard->setHolder()->setBirthdate('01/10/1979');
        $creditCard->setHolder()->setName('João Comprador'); // Equals in Credit Card
        $creditCard->setHolder()->setPhone()->withParameters(
            11,
            56273440
        );

        $creditCard->setHolder()->setDocument()->withParameters(
            'CPF',
            '79507445021'
        );

        // Set the Payment Mode for this payment request
        $creditCard->setMode('DEFAULT');

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        //Get the crendentials and register the boleto payment
        $result = $creditCard->register(
            $this->credentials
        );

        return $result;
    }
}
