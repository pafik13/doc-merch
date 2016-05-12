<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Slide
 *
 * @ORM\Table(name="slide")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SlideRepository")
 * @ExclusionPolicy("none")
 * @Vich\Uploadable
 */
class Slide
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
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text", nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Subcategory", inversedBy="slides")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id",nullable=true, onDelete="SET NULL")
     * @Exclude
     */
    private $subcategory;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    private $number;

    /**
     *
     * @Vich\UploadableField(mapping="slides", fileNameProperty="name")
     *
     * @var File
     */
    private $file;

    public function __construct($name=null, $image = null, Subcategory $subcategory=null, $number = null)
    {
        $this->name = $name;
        $this->path = $image;
        $this->number = $number;
        $this->setSubcategory($subcategory);
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
     * Set image
     *
     * @param string $path
     * @return Slide
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get queue
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    public function getSubcategory()
    {
        return $this->subcategory;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSubcategory($subcategory)
    {
        if ($this->subcategory !== null) {
            $this->subcategory->removeSlide($this);
        }

        if ($subcategory !== null) {
            $subcategory->addSlide($this);
        }

        $this->subcategory = $subcategory;
        return $this;
    }


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return Slide
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

}
