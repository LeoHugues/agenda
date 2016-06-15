<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/15/16
 * Time: 6:48 AM
 */

namespace APIRestBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;


/**
 *  @Route("/users")
 */
class RestUserController extends FOSRestController
{

    /**
     *
     * @Rest\Get("/", name="agenda_api_users")
     * @return array
     */
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return array('users' => $users);
    }
}