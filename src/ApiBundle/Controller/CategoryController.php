<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Category;
use ApiBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $categoriesData = $this->getDoctrine()->getRepository('ApiBundle:Category')->findAll();
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
     * @return Response
     * @Route("", methods={"POST"}, name="categories_post")
     */
    public function saveAction(Request $request)
    {
        $data = $request->request->all();
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($category);
        $doctrine->flush();

        // $category = $this->get('jms_serializer')->serialize($category, 'json');

        return new JsonResponse(["message" => "Categoria salva com sucesso."], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("", methods={"PUT"}, name="categories_put")
     */
    public function updateAction(Request $request)
    {
        $data = $request->request->all();

        $category = $this->getDoctrine()->getRepository('ApiBundle:Category')->find($data['id']);

        if (!$category) {
            return $this->createNotFoundException('Category Not Found!');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->merge($category);
        $doctrine->flush();

        return new JsonResponse(["message" => "Categoria atualizada com sucesso."], 200);
    }

    /**
     * @param Category $category
     * @Route("/{id}", methods={"DELETE"}, name="categories_delete")
     */
    public function deleteAction(Category $category)
    {

    }
}
