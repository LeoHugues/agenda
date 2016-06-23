<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/23/16
 * Time: 12:45 AM
 */

namespace UserBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CRUDController extends Controller
{
    /**
     * @Route("/users/list", name="user_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('UserBundle:User')->findAll();
        
        
        return $this->render('UserBundle:CRUD:list.html.twig', array('users' => $users));
    }
}