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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use UserBundle\Entity\User;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


class RestUserController extends FOSRestController
{

    /**
     *
     * @Rest\Get("/users", name="agenda_api_users")
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

    /**
     *
     * @Rest\Get("/user/{userId}", name="agenda_api_user")
     * @Doc\ApiDoc(
     *     section="User",
     *     resource=true,
     *     description="Get user by id.",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     */
    public function getUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('UserBundle:User')->find($userId);

        return array('user' => $user);
    }

    /**
     *
     * @Rest\Post("/user/login", name="agenda_api_login")
     * @Doc\ApiDoc(
     *     section="User",
     *     resource=true,
     *     description="check username and password",
     *     statusCodes={
     *          200="Returned when successful",
     *     },
     *     requirements={
     *      {"name" = "username", "dataType" = "string", "requirement"="\d+", "description"="nom d'utilisateur"},
     *      {"name" = "password", "dataType" = "string", "requirement"="\d+", "description"="mot de passe de l'utilisateur"}
     *  },
     * )
     */
    public function loginAction(Request $request)
    {
        $username = $request->get("username");
        $password = $request->get("password");

        if(is_null($username) || is_null($password)) {
            return new JsonResponse(
                ["error" => 'Please verify all your inputs.'],
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }

        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');

        $user = $user_manager->findUserByUsername($username);
        if (is_null($user)) {
            return new JsonResponse(
                ["error" => 'Username or Password not valid.'],
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();

        if($encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
            return new JsonResponse(
                ["status" => 'CONNECTED'],
                Response::HTTP_OK,
                array('Content-type' => 'application/json')
            );
        } else {
            return new JsonResponse(
                ["error" => 'Username or Password not valid.'],
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }
    }
}