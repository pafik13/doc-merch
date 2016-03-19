<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ExclusionPolicy("none")
 */
class Category
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
     * @ORM\OneToMany(targetEntity="Subcategory", mappedBy="category", orphanRemoval=true)
     */
    private $subcategories;

    public function __construct($name=null)
    {
        $this->name = $name;
        $this->subcategories = new ArrayCollection();
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
     * @return Category
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
    
    public function getSubcategories()
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory)
    {
        if(!$this->subcategories->contains($subcategory)){
            $this->subcategories->add($subcategory);
        }
        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory)
    {
        if ($this->subcategories->contains($subcategory)) {
            $this->subcategories->removeElement($subcategory);
        }

        return $this;
    }

    public function findSubcategoryById($id){
        foreach($this->subcategories as $subcategory){
            if($subcategory->getId()==$id){
                return $subcategory;
            }
        }
        return false;
    }
}
