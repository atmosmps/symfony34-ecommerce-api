<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/", name="products_main")
     * @Method("GET")
     */
    public function index() {
        $productsData = $this->getDoctrine()->getRepository('ApiBundle:Product')->findAll();
        $products = $this->get('jms_serializer')->serialize($productsData, 'json');
        return new Response($products);
    }

    /**
     * @param Product $product
     * @return Response
     * @Route("/{id}", name="products_get")
     * @Method("GET")
     */
    public function getAction(Product $product)
    {
        $product = $this->get('jms_serializer')->serialize($product, 'json');
        return new Response($product);
    }

    /**
     * @Route("/save", name="products_post")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function save(Request $request) {
        $data = $request->request->all();

        $doctrine = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setContent($data['content']);
        $product->setSlug($data['slug']);
        $product->setPrice($data['price']);
        $product->setCreatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));
        $product->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));

        $doctrine->persist($product);
        $doctrine->flush();

        return new JsonResponse(['msg' => 'Produto inserido com sucesso!'], 200);
    }
}
