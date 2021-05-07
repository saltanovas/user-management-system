<?php

namespace App\Controller;

use App\Entity\Group;
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
     * @Route("/group/update/{groupId}", name="update_group")
     * @Method({"GET", "POST"})
     */
    public function update(Request $request, $groupId)
    {
        $form = $this->createForm(GroupFormType::class, $this->groupRepo->getById($groupId));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepo->insert($form->getData());
            $this->groupRepo->save();

            return $this->redirectToRoute('list_group');
        }

        return $this->render('group/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/group/delete/{groupId}", name="delete_group")
     * @Method({"DELETE"})
     */
    public function deleteGroup($groupId)
    {
        $this->groupRepo->delete($this->groupRepo->getById($groupId));
        $this->groupRepo->save();

        return $this->redirectToRoute('list_group');
    }

    /**
     * @Route("/group/delete/{groupId}/{userId}")
     * @Method({"DELETE"})
     */
    public function deleteUserFromGroup($groupId, $userId)
    {
        $this->groupRepo->removeUser($groupId, $userId);

        return $this->redirectToRoute('list_group');
    }
}
