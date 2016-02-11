<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * Slide
 *
 * @ORM\Table(name="slide")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SlideRepository")
 * @ExclusionPolicy("none")
 */
class Slide
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Exclude
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Subcategory", inversedBy="slides")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id",nullable=true, onDelete="SET NULL")
     * @Exclude
     */
    private $subcategory;

    /**
     * @var int
     *
     * @ORM\Column(name="queue", type="integer", nullable=true)
     */
    private $queue;


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
     * @param string $image
     * @return Slide
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set queue
     *
     * @param integer $queue
     * @return Slide
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Get queue
     *
     * @return integer 
     */
    public function getQueue()
    {
        return $this->queue;
    }

    public function getSubcategory()
    {
        return $this->subcategory;
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
}
