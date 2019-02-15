<?php

namespace ApiBundle\Traits;

use Symfony\Component\Form\FormInterface;

trait FormErrorValidator
{
    public function getErros(FormInterface $form)
    {
        $erros = [];
        foreach ($form->getErrors() as $e) {
            $erros[] = $e->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($e = $this->getErros($childForm)) {
                    $erros[$childForm->getName()] = $e;
                }
            }
        }

        return $erros;
    }
}
