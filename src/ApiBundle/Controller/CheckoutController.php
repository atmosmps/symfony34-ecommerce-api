<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\UserOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CheckoutController
 * @package ApiBundle\Controller
 * @Route("checkout", name="checkout")
 */
class CheckoutController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("", methods={"POST"}, name="checkout_index")
     */
    public function indexAction(Request $request)
    {
        $data = $request->request->all();

        $userOrder = new UserOrder();
        $userOrder->setItems(serialize($data['items']));
        $userOrder->setUser($this->getUser());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($userOrder);
        $manager->flush();

        return new JsonResponse(['msg' => $userOrder->getId()], 200);
    }
}
