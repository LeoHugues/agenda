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
        // replace this example code with whatever you need
        return $this->render('ContactBundle:groupe:list.html.twig', array());
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
            $groupe->setAdmin($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            $this->addFlash('notice', 'Le groupe a été ajouter avec succée !');
        }
        
        // replace this example code with whatever you need
        return $this->render('ContactBundle:groupe:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}