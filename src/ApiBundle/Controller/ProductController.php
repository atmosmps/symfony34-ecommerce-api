<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{

    public function indexAction()
    {
        $productsData = $this->getDoctrine()->getRepository('ApiBundle:Product')->findAll();
        $products = $this->get('jms_serializer')->serialize($productsData, 'json');
        return new Response($products);
    }

    public function getAction(Product $product)
    {
        $product = $this->get('jms_serializer')->serialize($product, 'json');
        return new Response($product);
    }

    public function saveAction(Request $request)
    {
        $data = $request->request->all();

        $doctrine = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setContent($data['content']);
        $product->setPrice($data['price']);

        $doctrine->persist($product);
        $doctrine->flush();

        return new JsonResponse(['msg' => 'Produto inserido com sucesso!'], 200);
    }

    public function updateAction(Request $request)
    {
        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $product = $doctrine->getRepository('ApiBundle:Product')->find($data['id']);

        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setContent($data['content']);
        $product->setPrice($data['price']);

        $manager->persist($product);
        $manager->flush();

        return new JsonResponse(['msg' => 'Produto atualizado com sucesso!'], 200);
    }

    public function deleteAction(Product $product)
    {
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($product);
        $doctrine->flush();
        return new JsonResponse(['msg' => 'Produto removido com sucesso!'], 200);
    }
}
