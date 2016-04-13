<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * Presentation
 *
 * @ORM\Table(name="presentation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PresentationRepository")
 * @ExclusionPolicy("none")
 */
class Presentation
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255)
     */
    private $template;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Exclude
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=true, onDelete="SET NULL")
     * @Exclude
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist"})
     * @ORM\JoinTable(name="presentation_categories",
     *      joinColumns={@ORM\JoinColumn(name="presentation_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="cascade")}
     *      )
     */
    private $categories;

    public function __construct(){
        $this->date = new \DateTime();
        $this->categories = new ArrayCollection();
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
     * @return Presentation
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
     * Set template
     *
     * @param string $template
     * @return Presentation
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Presentation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Presentation
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function findCategoryById($id){
        foreach($this->categories as $category){
            if($category->getId()==$id){
                return $category;
            }
        }
        return false;
    }

    public function addCategory(Category $category){
        if(!$this->categories->contains($category)){
            $this->categories->add($category);
        }
        return $this;
    }

    public function removeCategory(Category $category){
        if($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
        return $this;
    }
}
