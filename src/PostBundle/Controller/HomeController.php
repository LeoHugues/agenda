<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 18/03/2016
 * Time: 17:52
 */

namespace PostBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction() {
        return $this->render("PostBundle:Home:index.html.twig");
    }

    /**
     * @Route("/post/create")
     */
    public function createAction() {
        return $this->render("PostBundle:Form:create.html.twig");
    }
}