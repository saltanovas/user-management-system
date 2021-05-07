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

class UserController extends AbstractController
{
    private UserRepository $userRepo;
    private GroupRepository $groupRepo;

    public function __construct(UserRepository $userRepo, GroupRepository $groupRepo)
    {
        $this->userRepo = $userRepo;
        $this->groupRepo = $groupRepo;
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
        $groups = $this->groupRepo->findBy(array(), array('name' => 'ASC'));

        // hard coded form, saw is it possible to do the same thing inside a twig
        $form = $this->createFormBuilder(new User())
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
                'groups',
                EntityType::class,
                [
                    'label' => false,
                    'row_attr' => ['class' => 'form-group'],
                    'attr' =>
                    [
                        'class' => 'selectpicker form-control',
                        'multiple' => null,
                        'data-live-search' => "true",
                        'title' => "Choose groups to add student",
                        'data-selected-text-format' => 'count > 4',
                        'data-count-selected-text' => '{0} groups selected',
                        'data-size' => '7'
                    ],
                    'class' => Group::class,
                    'required' => false,
                    'choices'  => $groups,
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

    /**
     * @Route("/user/update/{userId}")
     * @Method({"GET", "POST"})
     */
    public function update(Request $request, $userId)
    {
        $groups = $this->groupRepo->findBy(array(), array('name' => 'ASC'));

        // hard coded form, saw is it possible to do the same thing in the twig
        $form = $this->createFormBuilder($this->userRepo->getById($userId))
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
                'groups',
                EntityType::class,
                [
                    'label' => false,
                    'row_attr' => ['class' => 'form-group'],
                    'attr' =>
                    [
                        'class' => 'selectpicker form-control',
                        'multiple' => null,
                        'data-live-search' => "true",
                        'title' => "Choose groups to add student",
                        'data-selected-text-format' => 'count > 4',
                        'data-count-selected-text' => '{0} groups selected',
                        'data-size' => '7'
                    ],
                    'class' => Group::class,
                    'required' => false,
                    'choices'  => $groups,
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
            $this->userRepo->insert($form->getData());
            $this->userRepo->save();

            return $this->redirectToRoute('list_user');
        }

        return $this->render('user/create.html.twig', array('form' => $form->createView()));
    }
}
