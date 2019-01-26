<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package ApiBundle\Controller
 * @Route("/categories")
 */
class CategoryController extends Controller
{
    /**
     * @Route("", methods={"GET"}, name="categories_index")
     */
    public function indexAction()
    {
        $categoriesData = $this->getDoctrine()->getRepository('ApiBundle:Product')->findAll();
        $categories = $this->get('jms_serializer')->serialize($categoriesData, 'json');
        return new Response($categories, 200);
    }

    /**
     * @param Category $category
     * @return Response
     * @Route("/{id}", methods={"GET"}, name="categories_get")
     */
    public function getAction(Category $category)
    {
        $category = $this->get('jms_seralizer')->serialize($category, 'json');
        return new Response($category, 200);
    }

    /**
     * @param Request $request
     * @Route("", methods={"POST"}, name=""categories_post")
     */
    public function saveAction(Request $request)
    {

    }

    public function updateAction(Request $request)
    {

    }

    /**
     * @param Category $category
     * @Route("/{id}", methods={"DELETE"}, name="categories_delete")
     */
    public function deleteAction(Category $category)
    {

    }
}
