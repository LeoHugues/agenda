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
use Nelmio\ApiDocBundle\Annotation as Doc;
use UserBundle\Entity\User;


/**
 *  @Route("/users")
 */
class RestUserController extends FOSRestController
{

    /**
     *
     * @Rest\Get("/", name="agenda_api_users")
     *
     * @Doc\ApiDoc(
     *     section="User",
     *     resource=true,
     *     description="Get the list of all users.",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     */
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return array('users' => $users);
    }
}