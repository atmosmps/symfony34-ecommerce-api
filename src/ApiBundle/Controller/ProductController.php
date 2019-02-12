<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use ApiBundle\Form\ProductType;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package ApiBundle\Controller
 * @Route("/products")
 */
class ProductController extends Controller
{
    /**
     * @return Response
     * @Route("", methods={"GET"}, name="product_index")
     */
    public function indexAction()
    {
        $productsData = $this->getDoctrine()->getRepository('ApiBundle:Product')->findAll();

        $products = $this->get('jms_serializer')->serialize(
            $productsData,
            'json',
            SerializationContext::create()->setGroups(['prod_index'])
        );

        return new Response($products, 200);
    }

    /**
     * @param Product $product
     * @return object|Response
     * @Route("/{id}", methods={"GET"}, name="product_get")
     */
    public function getAction(Product $product)
    {
        $product = $this->get('jms_serializer')->serialize(
            $product,
            'json',
            SerializationContext::create()->setGroups(['prod_index', 'prod_single'])
        );

        return new Response($product, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("", methods={"POST"}, name="product_save")
     */
    public function saveAction(Request $request)
    {
        $data = $request->request->all();

        $doctrine = $this->getDoctrine()->getManager();

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        $doctrine->persist($product);
        $doctrine->flush();

        return new JsonResponse(['msg' => 'Produto inserido com sucesso!'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("", methods={"PUT"}, name="product_update")
     */
    public function updateAction(Request $request)
    {
        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $product = $doctrine->getRepository('ApiBundle:Product')->find($data['id']);

        if (!$product) {
            return $this->createNotFoundException('Product Not Found!');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        $manager->merge($product);
        $manager->flush();

        return new JsonResponse(['msg' => 'Produto atualizado com sucesso!'], 200);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     * @Route("/{id}", methods={"DELETE"}, name="product_delete")
     */
    public function deleteAction(Product $product)
    {
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($product);
        $doctrine->flush();
        return new JsonResponse(['msg' => 'Produto removido com sucesso!'], 200);
    }
}
