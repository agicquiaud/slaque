<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class mainController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render("main/home.html.twig");
    }
     /**
      * @Route("/test/test/test")
      */
     public function test()
     {
         die("ok");
     }
}