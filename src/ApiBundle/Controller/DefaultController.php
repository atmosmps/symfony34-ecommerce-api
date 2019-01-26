<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        return new JsonResponse(["message" => "Ecommerce API REST - Symfony 3.4!"], 200);
    }
}
