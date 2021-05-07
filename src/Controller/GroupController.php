<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupFormType;
use App\Repository\GroupRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GroupController extends AbstractController
{
    private GroupRepository $groupRepo;

    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * @Route("/group", name="list_group")
     * @Method({"GET"})
     */
    public function index()
    {
        return $this->render(
            'group/index.html.twig',
            array('groups' => $this->groupRepo->findBy(array(), array('name' => 'ASC')))
        );
    }

    /**
     * @Route("/group/create", name="create_group")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {    
        $form = $this->createForm(GroupFormType::class, new Group());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepo->insert($form->getData());
            $this->groupRepo->save();

            return $this->redirectToRoute('list_group');
        }

        return $this->render('group/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/group/update/{group}", name="update_group")
     * @Method({"GET", "POST"})
     */
    public function update(Request $request, Group $group)
    {
        $form = $this->createForm(GroupFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepo->insert($form->getData());
            $this->groupRepo->save();

            return $this->redirectToRoute('list_group');
        }

        return $this->render('group/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/group/delete/{group}", name="delete_group")
     * @Method({"DELETE"})
     */
    public function deleteGroup(Group $group)
    {
        if(count($group->getUsers()) == 0){
            $this->groupRepo->delete($group);
            $this->groupRepo->save();
        }

        return $this->redirectToRoute('list_group');
    }

    /**
     * @Route("/group/delete/{group}/{user}")
     * @Method({"DELETE"})
     */
    public function deleteUserFromGroup(Group $group, User $user)
    {
        $this->groupRepo->removeUser($group, $user);

        return $this->redirectToRoute('list_group');
    }
}
