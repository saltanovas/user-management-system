<?php

namespace App\Controller;

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
     * @Route("/user", name="list_user")
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
     * @Route("/user/create", name="create_user")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $form = $this->createForm(UserFormType::class, new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepo->insert($form->getData());
            $this->userRepo->save();

            return $this->redirectToRoute('list_user');
        }

        return $this->render('user/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/user/update/{userId}")
     * @Method({"GET", "POST"})
     */
    public function update(Request $request, $userId)
    {
        $form = $this->createForm(UserFormType::class, $this->userRepo->getById($userId));
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepo->insert($form->getData());
            $this->userRepo->save();

            return $this->redirectToRoute('list_user');
        }

        return $this->render('user/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/user/delete/{userId}")
     * @Method({"DELETE"})
     */
    public function deleteUser($userId)
    {
        $this->userRepo->delete($this->userRepo->getById($userId));
        $this->userRepo->save();
        
        return $this->redirectToRoute('list_user');
    }

    /**
     * @Route("/user/delete/{userId}/{groupId}")
     * @Method({"DELETE"})
     */
    public function deleteGroupFromUser($userId, $groupId)
    {
        $this->userRepo->removeGroupFromUser($userId, $groupId);

        return $this->redirectToRoute('list_user');
    }
}
