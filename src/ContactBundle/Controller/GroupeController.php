<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/19/16
 * Time: 10:52 AM
 */

namespace ContactBundle\Controller;


use ContactBundle\Entity\Groupe;
use ContactBundle\Entity\MemberRequest;
use ContactBundle\Form\GroupeType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;


/**
 * Class CRUDController
 * @package ContactBundle\Controller
 */
class GroupeController extends Controller
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

    /**
     * @Route("/groupe/detail/{id}", name="contact_groupe_detail")
     */
    public function showGroupeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository('ContactBundle:Groupe')->find($id);
        $posts = $em->getRepository('PostBundle:Post')->findBy(
            array('groupe' => $groupe),
            array('createDate' => 'DESC')
        );

        return $this->render('ContactBundle:groupe:show.html.twig', array(
            'groupe' => $groupe,
            'posts'   => $posts
        ));
    }

    /**
     * @Route("/groupe/add/member/{idGroupe}", name="contact_groupe_get_list_add_member")
     */
    public function getListFriendToAddMemberToGroupe(Request $request, $idGroupe) {
        /** @var User $user */
        $user = $this->getUser();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository('ContactBundle:Groupe')->find($idGroupe);

        return $this->render('ContactBundle:groupe:users_list.html.twig', array(
            'users' => $this->getUsersNotInGroupe($user->getFriends(), $groupe),
            'groupe' => $groupe
        ));
    }

    /**
     * @param $users ArrayCollection
     * @param $groupe Groupe
     * @return ArrayCollection
     */
    private function getUsersNotInGroupe($users, $groupe) {
        $result = new ArrayCollection();

        foreach ($users as $user) {
            if (!$groupe->getUsers()->contains($user)) {
                $result->add($user);
            }
        }

        return $result;
    }

    /**
     * @Route("/groupe/add/member/{idMember}/{idGroupe}", name="contact_groupe_add_member")
     */
    public function addMemberToGroupe(Request $request, $idMember, $idGroupe) {
        /** @var User $user */
        $user = $this->getUser();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository('ContactBundle:Groupe')->find($idGroupe);
        $member = $em->getRepository('UserBundle:User')->find($idMember);
        $memberRequest = new MemberRequest();
        $memberRequest->setApplicant($this->getUser());
        $memberRequest->setRecipient($member);
        $memberRequest->setGroupe($groupe);

        $em->persist($memberRequest);
        $em->flush();

        $this->addFlash('notice', 'Une invitation a été envoyé à l\'utilisateur !');

        return $this->render('ContactBundle:groupe:show.html.twig', array(
            'groupe' => $groupe,
        ));
    }

    /**
     * @Route("/list/member/request", name="contact_groupe_list_members_request")
     */
    public function listMemberRequest(Request $request) {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $memberRequest = $em->getRepository('ContactBundle:MemberRequest')->findBy(array(
               'recipient' => $this->getUser(),
            ));

        return $this->render('ContactBundle:groupe/request:list.html.twig', array(
            'membersRequest' => $memberRequest,
        ));
    }

    /**
     * @Route("/remove/member/request/{requestId}", name="contact_remove_member_request")
     * @param Request $request
     * @param $requestId
     * @return RedirectResponse
     */
    public function removeMemberRequest(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        $memberRequest = $em->getRepository('ContactBundle:MemberRequest')->find($requestId);

        if ($memberRequest) {
            $em->remove($memberRequest);
            $em->flush();

            $this->addFlash('notice', 'Une demande a été envoyé à l\'utilisateur !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/accept/member/request/{requestId}", name="contact_accept_member_request")
     * @param Request $request
     * @param $requestId
     * @return RedirectResponse
     */
    public function acceptMemberRequest(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        /** @var MemberRequest $memberRequest */
        $memberRequest = $em->getRepository('ContactBundle:MemberRequest')->find($requestId);

        if ($memberRequest) {
            /** @var User $user */
            $user = $this->getUser();
            $memberRequest->setStatus(MemberRequest::COMFIRM);
            $groupe = $memberRequest->getGroupe();
            $groupe->addUser($user);
            $user->addGroupe($groupe);
            $em->persist($groupe);
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Invitation accepté, bienvenue dans : ' . $groupe->getName() . ' !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/reject/member/request/{requestId}", name="contact_reject_member_request")
     * @param Request $request
     * @param $requestId
     * @return RedirectResponse
     */
    public function rejectMemberRequest(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        $memberRequest = $em->getRepository('ContactBundle:MemberRequest')->find($requestId);

        if ($memberRequest) {
            $memberRequest->setStatus(MemberRequest::REJECT);
            $em->persist($memberRequest);
            $em->flush();

            $this->addFlash('notice', 'Invitation rejeté !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/left/groupe/{id}", name="contact_left_groupe")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function leftGroupe(Request $request, $id) {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Groupe $groupe */
        $groupe = $em->getRepository('ContactBundle:Groupe')->find($id);
        /** @var User $user */
        $user = $this->getUser();

        if ($groupe) {
            if ($groupe->getAdmin() == $user) {
                $membersRequest = $em->getRepository('ContactBundle:MemberRequest')->findBy(array(
                   'groupe' => $groupe,
                ));
                foreach ($membersRequest as $request) {
                    $em->remove($request);
                }
                $em->remove($groupe);
            } else {
                /** @var MemberRequest $memberRequest */
                $memberRequest = $em->getRepository('ContactBundle:MemberRequest')->getMemberRequest($user, $groupe);
                if ($memberRequest) {
                    $memberRequest->setStatus(MemberRequest::REJECT);
                    $em->persist($memberRequest);
                }
                $groupe->deleteUser($user);
                $user->deleteGroupe($groupe);
                $em->persist($user);
                $em->persist($groupe);

                $this->addFlash('notice', 'Vous ne faites plus parti du groupe : ' . $groupe->getName() . '.');
            }
            $em->flush();
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}