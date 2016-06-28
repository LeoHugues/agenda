<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/28/16
 * Time: 10:49 PM
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
use UserBundle\Entity\FriendRequest;
use UserBundle\Entity\User;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

class RestContactController extends FOSRestController
{
    /**
     * @Rest\Post("/add/friend/request", name="contact_add_friend_request")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Doc\ApiDoc(
     *     section="FriendRequest",
     *     resource=true,
     *     description="Faire une demande d'amitié",
     *     statusCodes={
     *          200="Returned when successful",
     *     },
     *     requirements={
     *          {"name" = "friendId", "dataType" = "int", "requirement"="obligatoire", "description"="id de l'amis à ajouter"},
     *          {"name" = "userId", "dataType" = "int", "requirement"="obligatoire", "description"="id du demandeur (l'utilisateur)"}
     *     },
     * )
     */
    public function addFriendAction(Request $request) {
        $friendId = $request->get("friendId");
        $userId = $request->get("userId");
        
        $em = $this->getDoctrine()->getManager();

        $friend = $em->getRepository('UserBundle:User')->find($friendId);
        $user = $em->getRepository('UserBundle:User')->find($userId);

        if ($friend && $user) {
            $friendRequest = new FriendRequest();
            $friendRequest->setApplicant($user);
            $friendRequest->setRecipient($friend);

            $em->persist($friendRequest);
            $em->flush();

            return new JsonResponse(
                ["status" => 'ADDED'],
                Response::HTTP_OK,
                array('Content-type' => 'application/json')
            );
        } else {
            return new JsonResponse(
                ["error" => 'Invalid ID'],
                Response::HTTP_BAD_REQUEST,
                array('Content-type' => 'application/json')
            );
        }
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
    public function removeFriendRequestAction(Request $request, $requestId) {
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
    public function acceptFriendRequestAction(Request $request, $requestId) {
        $em = $this->getDoctrine()->getManager();
        $friendRequest = $em->getRepository('UserBundle:FriendRequest')->find($requestId);

        if ($friendRequest) {
            $friendRequest->setStatus(FriendRequest::COMFIRM);
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
    public function rejectFriendRequestAction(Request $request, $requestId) {
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