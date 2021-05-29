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
     * @Route("/api/groups", name="get_groups")
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
     * @Route("/api/group", name="create_group")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {    
        $form = $this->createForm(GroupFormType::class, new Group());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepo->insert($form->getData());
            $this->groupRepo->save();

            return $this->redirectToRoute('get_groups');
        }

        return $this->render('group/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/api/group/{group}", name="put_group")
     * @Method({"PUT"})
     */
    public function update(Request $request, Group $group)
    {
        $form = $this->createForm(GroupFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepo->insert($form->getData());
            $this->groupRepo->save();

            return $this->redirectToRoute('get_groups');
        }

        return $this->render('group/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/api/group/{group}", name="delete_group")
     * @Method({"DELETE"})
     */
    public function deleteGroup(Group $group)
    {
        if(count($group->getUsers()) == 0){
            $this->groupRepo->delete($group);
            $this->groupRepo->save();
        }

        return $this->redirectToRoute('get_groups');
    }

    /**
     * @Route("/api/group/{group}/{user}", name="delete_user_from_group")
     * @Method({"DELETE"})
     */
    public function deleteUserFromGroup(Group $group, User $user)
    {
        $this->groupRepo->removeUser($group, $user);

        return $this->redirectToRoute('get_groups');
    }
}
