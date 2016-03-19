<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
/**
 * Subcategory
 *
 * @ORM\Table(name="subcategory")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubcategoryRepository")
 * @ExclusionPolicy("none")
 */
class Subcategory
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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="subcategories")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id",nullable=true, onDelete="SET NULL")
     * @Exclude
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Slide", mappedBy="subcategory", orphanRemoval=true)
     */
    private $slides;

    public function __construct($name=null, Category $category=null)
    {
        $this->name=$name;
        $this->setCategory($category);
        $this->slides = new ArrayCollection();
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
     * @return Subcategory
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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        if ($this->category !== null) {
            $this->category->removeSubcategory($this);
        }

        if ($category !== null) {
            $category->addSubcategory($this);
        }

        $this->category = $category;
        return $this;
    }

    public function getSlides()
    {
        return $this->slides;
    }

    public function addSlide(Slide $slide)
    {
        if(!$this->slides->contains($slide)){
            $this->slides->add($slide);
        }
        return $this;
    }

    public function removeSlide(Slide $slide)
    {
        if ($this->slides->contains($slide)) {
            $this->slides->removeElement($slide);
        }

        return $this;
    }
}
