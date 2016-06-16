<?php

namespace ContactBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity(repositoryClass="ContactBundle\Repository\GroupeRepository")
 */
class Groupe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", mappedBy="groupes")
     * @ORM\Column(nullable=true)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")     
     * @ORM\Column(nullable=false)
     */
    private $admin;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Groupe
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUser($user)
    {
        $this->users->add($user);
    }

    /**
     * @param User $user
     */
    public function deleteUser($user)
    {
        $this->users->removeElement($user);
    }

    /**
     * @return User
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param User $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }
}
