<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/30/16
 * Time: 11:20 AM
 */

namespace APIRestBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\FOSRestController;
use PostBundle\Entity\Post;
use UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

class RestPostController extends FOSRestController
{
    /**
     *
     * @Rest\Get("/post/{id}", name="agenda_api_posts")
     * @Doc\ApiDoc(
     *     section="Post",
     *     resource=true,
     *     description="Get the agenda user.",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     */
    public function getUserAgendaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);
        $posts = $em->getRepository('PostBundle:Post')->getAgendaByUser($user);

        return array('posts' => $this->getGoodPost($posts, $user));
    }

    /**
     *
     * @Rest\Get("/post/groupe/{id}", name="agenda_api_groupe_posts")
     * @Doc\ApiDoc(
     *     section="Post",
     *     resource=true,
     *     description="Get the list of groupe posts.",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     */
    public function getPostsByGroupeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository('ContactBundle:Groupe')->find($id);
        $posts = $em->getRepository('PostBundle:Post')->findBy(
            array('groupe' => $groupe),
            array('concerneDate' => 'DESC')
        );

        return array('posts' => $posts);
    }

    /**
     * @param $posts ArrayCollection
     * @param $user  User
     * @return ArrayCollection
     */
    private function getGoodPost($posts, $user) {
        if ($user) {
            $result = new ArrayCollection();

            /**
             * @var Post $post
             */
            foreach ($posts as $post) {
                if ($post->getGroupe()->getUsers()->contains($user)) {
                    $result->add($post);
                }
            }

            return $result;
        } else {
            return null;
        }
    }
}