<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GroupController extends AbstractController
{
    private GroupRepository $groupRepo;
    private UserRepository $userRepo;

    public function __construct(GroupRepository $groupRepo, UserRepository $userRepo)
    {
        $this->groupRepo = $groupRepo;
        $this->userRepo = $userRepo;
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
        $users = $this->userRepo->findBy(array(), array('name' => 'ASC'));

        // hard coded form, saw is it possible to do the same thing in the twig
        $form = $this->createFormBuilder(new Group())
            ->add(
                'name',
                TextType::class,
                [
                    'label' => "Name:",
                    'row_attr' => ['class' => 'form-group'],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'users',
                EntityType::class,
                [
                    'label' => false,
                    'row_attr' => ['class' => 'form-group'],
                    'attr' =>
                    [
                        'class' => 'selectpicker form-control',
                        'multiple' => null,
                        'data-live-search' => "true",
                        'title' => "Choose students to add",
                        'data-selected-text-format' => 'count > 4',
                        'data-count-selected-text' => '{0} users selected',
                        'data-size' => '7'
                    ],
                    'class' => User::class,
                    'required' => false,
                    'by_reference' => false, //this one is important!!!
                    'choices'  => $users,
                    'multiple' => true,
                    'choice_label' => 'name',
                    'choice_value' => 'id'
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Create',
                    'attr' => ['class' => 'btn btn-primary mt-3']
                ]
            )
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepo->insert($form->getData());
            $this->groupRepo->save();

            return $this->redirectToRoute('list_group');
        }

        return $this->render('group/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/group/delete/{groupId}")
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

    /**
     * @Route("/group/update/{groupId}", name="create_group")
     * @Method({"GET", "POST"})
     */
    public function update(Request $request, $groupId)
    {
        $users = $this->userRepo->findBy(array(), array('name' => 'ASC'));

        // hard coded form, saw is it possible to do the same thing inside a twig
        $form = $this->createFormBuilder($this->groupRepo->getById($groupId))
            ->add(
                'name',
                TextType::class,
                [
                    'label' => "Name:",
                    'row_attr' => ['class' => 'form-group'],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'users',
                EntityType::class,
                [
                    'label' => false,
                    'row_attr' => ['class' => 'form-group'],
                    'attr' =>
                    [
                        'class' => 'selectpicker form-control',
                        'multiple' => null,
                        'data-live-search' => "true",
                        'title' => "Choose students to add",
                        'data-selected-text-format' => 'count > 4',
                        'data-count-selected-text' => '{0} users selected',
                        'data-size' => '7'
                    ],
                    'class' => User::class,
                    'required' => false,
                    'by_reference' => false, //this one is important!!!
                    'choices'  => $users,
                    'multiple' => true,
                    'choice_label' => 'name',
                    'choice_value' => 'id'
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Create',
                    'attr' => ['class' => 'btn btn-primary mt-3']
                ]
            )
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepo->insert($form->getData());
            $this->groupRepo->save();

            return $this->redirectToRoute('list_group');
        }

        return $this->render('group/create.html.twig', array('form' => $form->createView()));
    }
}
