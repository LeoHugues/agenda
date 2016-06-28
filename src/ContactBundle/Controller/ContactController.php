<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/28/16
 * Time: 7:41 PM
 */

namespace ContactBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\FriendRequest;
use UserBundle\Entity\User;

class ContactController extends Controller
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
        $friendsRequest = $em->getRepository('UserBundle:FriendRequest')->findBy(array(
            'recipient' => $this->getUser(),
        ));
        
        return $this->render('@User/FriendRequest/list.html.twig', array('friendsRequest' => $friendsRequest));
    }

    /**
     * @Route("/remove/friend/request/{requestId}", name="contact_remove_friend_request")
     * @param Request $request
     * @param $requestId
     * @return RedirectResponse
     */
    public function removeFriendRequest(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        $friendRequest = $em->getRepository('UserBundle:FriendRequest')->find($requestId);

        if ($friendRequest) {
            $em->remove($friendRequest);
            $em->flush();

            $this->addFlash('notice', 'Une demande a été envoyé à l\'utilisateur !');
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
        $friendRequest = $em->getRepository('UserBundle:FriendRequest')->find($requestId);

        if ($friendRequest) {
            $friendRequest->setStatus(FriendRequest::COMFIRM);
            $applicant = $friendRequest->getApplicant();
            $recipient = $friendRequest->getRecipient();
            $applicant->addFriend($recipient);
            $em->persist($applicant);
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
        $friendRequest = $em->getRepository('UserBundle:FriendRequest')->find($requestId);

        if ($friendRequest) {
            $friendRequest->setStatus(FriendRequest::REJECT);
            $em->persist($friendRequest);
            $em->flush();

            $this->addFlash('notice', 'La demande a bien été rejeté !');
        } else {
            $this->addFlash('warning', 'échec de l\opértion...');
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}