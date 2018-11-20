<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * @Route("/login", name="user_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        if ($this->getUser())
        {
            $this->addFlash("warning", "Vous êtes déjà connecté !");
            return $this->redirectToRoute("home");
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();
        return $this->render("user/login.html.twig", [
            'last_username' => $username,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="user_register", methods="GET|POST")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser())
        {
            $this->addFlash("warning", "Vous êtes déjà inscrit");
            return $this->redirectToRoute("home");
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/view", name="user_show", methods="GET")
     */
    public function show(): Response
    {
        $user = $this->getUser();
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout(){}
}
