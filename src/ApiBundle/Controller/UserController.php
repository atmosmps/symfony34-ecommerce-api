<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package ApiBundle\Controller
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @Route("", methods={"GET"}, name="users_index")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $usersData = $this->getDoctrine()
                        ->getRepository('ApiBundle:User')
                        ->findAllUsers();

        $data = $this->get('ApiBundle\Service\Pagination\PaginationFactory')->paginate($usersData, $request);

        $users = $this->get('jms_serializer')
                    ->serialize($data, 'json');

        return new Response($users, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/{id}", methods={"GET"}, name="users_get")
     */
    public function getAction(User $user)
    {
        $user = $this->get('jms_seralizer')->serialize($user, 'json');
        return new Response($user, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("", methods={"POST"}, name="users_post")
     */
    public function saveAction(Request $request)
    {
        $data = $request->request->all();
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($user);
        $doctrine->flush();

        // $user = $this->get('jms_serializer')->serialize($user, 'json');

        return new JsonResponse(["message" => "Usuario salvo com sucesso."], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("", methods={"PUT"}, name="users_put")
     */
    public function updateAction(Request $request)
    {
        $data = $request->request->all();

        $user = $this->getDoctrine()->getRepository('ApiBundle:User')->find($data['id']);

        if (!$user) {
            return $this->createNotFoundException('User Not Found!');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->merge($user);
        $doctrine->flush();

        return new JsonResponse(["message" => "Usuario atualizado com sucesso."], 200);
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @Route("/{id}", methods={"DELETE"}, name="users_delete")
     */
    public function deleteAction(User $user)
    {
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($user);
        $doctrine->flush();
        return new JsonResponse(['message' => 'Usuario Removido com Sucesso!'], 200);
    }
}
