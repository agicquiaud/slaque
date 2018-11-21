<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\UserGroup;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/group")
 */
class GroupController extends Controller
{

    /**
     * @Route("/create", name="group_create", methods="GET|POST")
     */
    public function create(Request $request): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userGroup = new UserGroup();
            $userGroup->setGroupe($group);
            $userGroup->setAdmin(true);
            $userGroup->setUser($this->getUser());
            $group->addUserGroup($userGroup);
            $em->persist($group);
            $em->persist($userGroup);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('group/create.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_show", methods="GET")
     */
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', ['group' => $group]);
    }

    /**
     * @Route("/edit", name="group_edit", methods="GET|POST")
     */
    public function edit(Request $request, Group $group): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home', ['id' => $group->getId()]);
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="group_delete", methods="DELETE")
     */
    public function delete(Request $request, Group $group): Response
    {
        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }
}
