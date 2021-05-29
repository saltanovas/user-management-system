<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/api/users", name="get_users")
     * @Method({"GET"})
     */
    public function index()
    {
        return $this->render(
            'user/index.html.twig',
            array('users' => $this->userRepo->findBy(array(), array('name' => 'ASC')))
        );
    }

    /**
     * @Route("/api/user", name="post_user")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $form = $this->createForm(UserFormType::class, new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepo->insert($form->getData());
            $this->userRepo->save();

            return $this->redirectToRoute('get_users');
        }

        return $this->render('user/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/api/user/{user}", name="put_user")
     * @Method({"PUT"})
     */
    public function update(Request $request, User $user)
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepo->insert($form->getData());
            $this->userRepo->save();

            return $this->redirectToRoute('get_users');
        }

        return $this->render('user/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/api/user/{user}", name="delete_user")
     * @Method({"DELETE"})
     */
    public function deleteUser(User $user)
    {
        $this->userRepo->delete($user);
        $this->userRepo->save();
        
        return $this->redirectToRoute('get_users');
    }

    /**
     * @Route("/api/user/{user}/{group}", name="delete_group_from_user")
     * @Method({"DELETE"})
     */
    public function deleteGroupFromUser(User $user, Group $group)
    {
        $this->userRepo->removeGroupFromUser($user, $group);

        return $this->redirectToRoute('get_users');
    }
}
