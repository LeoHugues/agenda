<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/19/16
 * Time: 10:52 AM
 */

namespace ContactBundle\Controller;


use ContactBundle\Entity\Groupe;
use ContactBundle\Form\GroupeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;


/**
 * Class CRUDController
 * @package ContactBundle\Controller
 */
class CRUDController extends Controller
{
    /**
     * @Route("/groupe/list", name="contact_groupe_list")
     */
    public function groupeListAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getEntityManager();

        $groupes = $em->getRepository('ContactBundle:Groupe')->findByUser($user);

        return $this->render('ContactBundle:groupe:list.html.twig', array(
            'groupes' => $groupes,
        ));
    }
    /**
     * @Route("/groupe/add", name="contact_groupe_add")
     */
    public function groupeAddAction(Request $request)
    {
        $form = $this->createForm(new GroupeType(), new Groupe());
        $form->add('ajouter', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Groupe $groupe */
            $groupe = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $groupe->setAdmin($user);
            $groupe->addUser($user);
            $user->getGroups()->add($groupe);
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Le groupe a été ajouter avec succée !');

            return $this->redirectToRoute('post_index_list');
        }
        
        return $this->render('ContactBundle:groupe:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}