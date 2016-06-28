<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/27/16
 * Time: 9:49 PM
 */

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FriendRequest
 *
 * @ORM\Table(name="friend_request")
 * @ORM\Entity()
 */
class FriendRequest
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="friendRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
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
}