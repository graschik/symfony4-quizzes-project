<?php

namespace App\Controller;



use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    private $userService;

    private const USERS_ON_PAGE = 15;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/admin/users",name="user.list")
     *
     * @param Request $request
     * @return Response
     */
    public function listUsers(Request $request)
    {
        $this->userService->createPaginator(self::USERS_ON_PAGE, $request);

        return $this->render('users/users.html.twig', [
            'pagination' => $this->userService->getPaginator(),
        ]);
    }

    /**
     * @Route(
     *     "/user/update-is-active/{id}",
     *     name="user.update.is_active",
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateUserIsActive(Request $request, $id)
    {
        $user = $this->userService->find($id);

        $this->userService->changeIsActive($user);

        $this->userService->commit($user);

        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

    /**
     * @Route(
     *     "admin/user/delete/{id}",
     *     name="user.delete",
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteUser(Request $request, $id)
    {
        $user = $this->userService->find($id);

        $this->userService->delete($user);

        $this->userService->commit($user);

        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }



}

