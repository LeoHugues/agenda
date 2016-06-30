<?php

namespace PostBundle\Entity;

use ContactBundle\Entity\Discipline;
use ContactBundle\Entity\Groupe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="PostBundle\Repository\PostRepository")
 */
class Post
{
    const TEST_PRIORITY         = 0;
    const HOME_WORK_PRIORITY    = 1;
    const EXERCICE_PRIORITY     = 2;
    const TODO_PRIORITY         = 3;
    const INFORMATION_PRIORITY  = 4;

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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="date")
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="concerneDate", type="date")
     */
    private $concerneDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=50)
     */
    private $priority;

    /**
     * Tous les commentaires faits pour ce post
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;

    /**
     * Le créateur du post
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     */
    private $publisher;

    /**
     * Le groupe dans lequel le poste a été publié | peut être null
     *
     * @ORM\ManyToOne(targetEntity="ContactBundle\Entity\Groupe", inversedBy="posts")
     * @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
     */
    private $groupe;

    /**
     * Un post peut concerner plusieurs discipline à la fois ex: math / algo / physique.
     *
     * @ORM\ManyToMany(targetEntity="ContactBundle\Entity\Discipline")
     * @ORM\Column(nullable=true)
     */
    private $discipline;


    public  function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->discipline = new ArrayCollection();
        $this->createDate = new \DateTime();
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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Post
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set concerneDate
     *
     * @param \DateTime $concerneDate
     * @return Post
     */
    public function setConcerneDate($concerneDate)
    {
        $this->concerneDate = $concerneDate;

        return $this;
    }

    /**
     * Get concerneDate
     *
     * @return \DateTime 
     */
    public function getConcerneDate()
    {
        return $this->concerneDate;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set priority
     *
     * @param string $priority
     * @return Post
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     */
    public function addComments($comment)
    {
        $this->comments->add($comment);
    }

    /**
     * @param Comment $comment
     */
    public function deleteComments($comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param mixed $publisher
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
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
