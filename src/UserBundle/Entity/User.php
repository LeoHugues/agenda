<?php

namespace UserBundle\Entity;

use ContactBundle\Entity\Discipline;
use ContactBundle\Entity\Groupe;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 *
 * @ExclusionPolicy("all")
 *
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     *
     */
    protected $id;

    /**
     * @Expose
     */
    protected $username;

    /**
     * @Expose
     */
    protected $email;
    
    protected $groups;
    
    protected $roles;

    /**
     * Un utilisateur peu faire parti de plusieurs groupes
     *
     * @ORM\ManyToMany(targetEntity="ContactBundle\Entity\Groupe", inversedBy="users")
     * @ORM\JoinTable(name="friend_groupes_users_index")
     * @Expose
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity="User", cascade={"persist"})
     * @ORM\JoinTable(name="friends",
     *     joinColumns={@ORM\JoinColumn(name="user_a_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_b_id", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $friends;

    /**
     * Un utilisateur peut être intéressé par plusieurs disciplines
     *
     * @ORM\ManyToMany(targetEntity="ContactBundle\Entity\Discipline")
     * @ORM\Column(nullable=true)
     * @Expose
     */
    private $discipline;

    /**
     * Toutes les demandes d'amis reçu parl'utilisateur;
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="FriendRequest", mappedBy="user")
     */
    private $friendRequests;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->discipline = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->friendRequests = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groupes;
    }

    /**
     * @param Groupe $groupe
     */
    public function addGroupe($groupe)
    {
        $this->groupes->add($groupe);
    }

    /**
     * @param Groupe $groupe
     */
    public function deleteGroupe($groupe)
    {
        $this->groupes->removeElement($groupe);
    }

    /**
     * @return ArrayCollection
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * @param Discipline $discipline
     */
    public function addDiscipline($discipline)
    {
        $this->discipline->add($discipline);
    }

    /**
     * @param Discipline $discipline
     */
    public function deleteDiscipline($discipline)
    {
        $this->discipline->removeElement($discipline);
    }

    /**
     * @return ArrayCollection
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param User $user
     */
    public function addFriend($user)
    {
        $this->friends->add($user);
    }

    /**
     * @param User $user
     */
    public function deleteFriend($user)
    {
        $this->friends->removeElement($user);
    }

    /**
     * @return mixed
     */
    public function getFriendRequests()
    {
        return $this->friendRequests;
    }

    /**
     * @param FriendRequest $friendRequest
     */
    public function addFriendRequests($friendRequest)
    {
        $this->friendRequests->add($friendRequest);
    }

    /**
     * @param FriendRequest $friendRequest
     */
    public function deleteFriendRequests($friendRequest)
    {
        $this->friendRequests->removeElement($friendRequest);
    }
}
