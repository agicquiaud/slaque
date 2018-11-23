<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class mainController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
            return $this->render("main/home.html.twig", [
                'user' => $this->getUser(),
            ]);
    }
     /**
      * @Route("/test/test/test")
      */
     public function test()
     {
         die("ok");
     }
}