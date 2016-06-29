<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 29/06/2016
 * Time: 15:38
 */

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * FriendRequest
 *
 * @ORM\Table(name="member_request")
 * @ORM\Entity(repositoryClass="ContactBundle\Repository\MemberRequestRepository")
 */
class MemberRequest
{
    const WAITING = 0;
    const COMFIRM = 1;
    const REJECT  = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * Le groupe de la demande
     *
     * @var Groupe
     * @ORM\ManyToOne(targetEntity="ContactBundle\Entity\Groupe", cascade={"persist"})
     */
    private $groupe;

    /**
     * Le demandeur
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     */
    private $applicant;

    /**
     * Receveur
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     */
    protected $recipient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="date")
     */
    private $createDate;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false, options={"default"=0})
     */
    private $status;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->status = self::WAITING;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getApplicant()
    {
        return $this->applicant;
    }

    /**
     * @param User $applicant
     */
    public function setApplicant($applicant)
    {
        $this->applicant = $applicant;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param Groupe $groupe
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
    }
}