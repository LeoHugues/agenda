<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/28/16
 * Time: 7:41 PM
 */

namespace ContactBundle\Controller;


use ContactBundle\Entity\FriendRequest;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;

class FriendController extends Controller
{
    /**
     * @Route("/add/friend/request/{friendId}", name="contact_add_friend_request")
     * @param Request $request
     * @param int $friendId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addFriendAction(Request $request, $friendId) {
        $em = $this->getDoctrine()->getManager();

        $friend = $em->getRepository('UserBundle:User')->find($friendId);

        if ($friend) {
            $friendRequest = new FriendRequest();
            $friendRequest->setApplicant($this->getUser());
            $friendRequest->setRecipient($friend);
            
            $em->persist($friendRequest);
            $em->flush();

            $this->addFlash('notice', 'Une demande a été envoyé à l\'utilisateur !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/list/friends/request", name="contact_list_friend_request")
     */
    public function listFriendRequestAction() {
        $em = $this->getDoctrine()->getManager();
        $friendsRequest = $em->getRepository('ContactBundle:FriendRequest')->findBy(array(
            'recipient' => $this->getUser(),
        ));
        
        return $this->render('ContactBundle:friend/request:list.html.twig', array('friendsRequest' => $friendsRequest));
    }

    /**
     * @Route("/remove/friend/request/{requestId}", name="contact_remove_friend_request")
     * @param Request $request
     * @param $requestId
     * @return RedirectResponse
     */
    public function removeFriendRequest(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        $friendRequest = $em->getRepository('ContactBundle:FriendRequest')->find($requestId);

        if ($friendRequest) {
            $em->remove($friendRequest);
            $em->flush();

            $this->addFlash('notice', 'La demande à bien été supprimé !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/accept/friend/request/{requestId}", name="contact_accept_friend_request")
     * @param Request $request
     * @param $requestId
     * @return RedirectResponse
     */
    public function acceptFriendRequest(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        $friendRequest = $em->getRepository('ContactBundle:FriendRequest')->find($requestId);

        if ($friendRequest) {
            $friendRequest->setStatus(FriendRequest::COMFIRM);
            $applicant = $friendRequest->getApplicant();
            $recipient = $friendRequest->getRecipient();
            $applicant->addFriend($recipient);
            $recipient->addFriend($applicant);
            $em->persist($applicant);
            $em->persist($recipient);
            $em->persist($friendRequest);
            $em->flush();

            $this->addFlash('notice', 'La demande a bien été accepté !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/reject/friend/request/{requestId}", name="contact_reject_friend_request")
     * @param Request $request
     * @param $requestId
     * @return RedirectResponse
     */
    public function rejectFriendRequest(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        /** @var FriendRequest $friendRequest */
        $friendRequest = $em->getRepository('ContactBundle:FriendRequest')->find($requestId);
        /** @var User $user */
        $user = $this->getUser();

        if ($friendRequest) {
            if ($friendRequest->getStatus() == FriendRequest::COMFIRM) {
                $user->deleteFriend($friendRequest->getApplicant());
                $friendRequest->getApplicant()->deleteFriend($user);
            }
            $friendRequest->setStatus(FriendRequest::REJECT);
            $em->persist($friendRequest);
            $em->persist($user);
            $em->persist($friendRequest->getApplicant());
            $em->flush();

            $this->addFlash('notice', 'La demande a bien été rejeté !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/remove/friend/{id}", name="contact_remove_friend")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function removeFriend(Request $request, $id) {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $friend */
        $friend = $em->getRepository('UserBundle:User')->find($id);
        /** @var User $user */
        $user = $this->getUser();

        if ($friend) {
            /** @var FriendRequest $friendRequest */
            $friendRequest = $em->getRepository('ContactBundle:FriendRequest')->getFriendRequest($user, $friend);
            if ($friendRequest) {
                $friendRequest->setStatus(FriendRequest::REJECT);
                $em->persist($friendRequest);
            }
            $user->deleteFriend($friend);
            $friend->deleteFriend($user);
            $em->persist($user);
            $em->persist($friend);
            $em->flush();

            $this->addFlash('notice', 'L\utilisateur ' . $friend->getUsername() . ' à bien été retiré de vos amis.');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/list/friends", name="contact_list_friend")
     */
    public function getFriendAction() {
        return $this->render('ContactBundle:friend:list.html.twig', array(
            'users' => $this->getUser()->getFriends()
        ));
    }
}