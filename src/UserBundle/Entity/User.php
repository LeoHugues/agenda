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

    /**
     * Un utilisateur peu faire parti de plusieurs groupes
     *
     * @ORM\ManyToMany(targetEntity="ContactBundle\Entity\Groupe", inversedBy="users")
     * @ORM\JoinTable(name="users_groups")
     * @Expose
     */
    private $groupes;

    /**
     * Un utilisateur peut être intéressé par plusieurs disciplines
     *
     * @ORM\ManyToMany(targetEntity="ContactBundle\Entity\Discipline")
     * @ORM\Column(nullable=true)
     * @Expose
     */
    private $discipline;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->discipline = new ArrayCollection();
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
}
