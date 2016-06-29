<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 18/03/2016
 * Time: 17:52
 */

namespace PostBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use PostBundle\Entity\Post;
use PostBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;

class PostController extends Controller
{
    /**
     * @Route("/", name="post_index_list")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository('PostBundle:Post')->getAgendaByUser($user);

        return $this->render("PostBundle:Home:index.html.twig", array(
            'user'  => $user,
            'posts' => $this->getGoodPost($posts, $user),
        ));
    }

    /**
     * @param $posts ArrayCollection
     * @param $user  User
     * @return ArrayCollection
     */
    private function getGoodPost($posts, $user) {
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
    }

    /**
     * @Route("/post/create/{groupeId}", name="post_create")
     */
    public function createAction(Request $request, $groupeId) {
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository('ContactBundle:Groupe')->find($groupeId);
        $post = new Post();
        $post->setGroupe($groupe);
        $form = $this->createForm(new PostType(), $post);
        $form->add('envoyer', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {

            /** @var Post $post */
            $post = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $post->setPublisher($user);
            $post->setGroupe($groupe);
            $em->persist($post);
            $em->flush();

            $this->addFlash('notice', 'Le post a été créé avec succée !');

            return $this->redirectToRoute('contact_groupe_detail', array('id' => $groupeId));
        }


        return $this->render("PostBundle:Form:create.html.twig", array(
            'form'   => $form->createView(),
            'groupe' => $groupe,
        ));
    }
}