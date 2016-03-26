<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     */
    private $id;
    /**
     * @ORM\Column(name="name", type="string", length = 255, nullable=true)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text", nullable=true)
     */
    private $path;

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
    private $number;

    /**
     * @Assert\File(maxSize="6000000")
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

    /**
     * Set queue
     *
     * @param integer $number
     * @return Slide
     */
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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->name
            ? null
            : $this->getUploadRootDir().'/'.$this->name;
    }

    public function getWebPath()
    {
        return null === $this->name
            ? null
            : $this->getUploadDir().'/'.$this->name;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads/documents';
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        //$this->name = $this->getFile()->getClientOriginalName();
        $this->path = $this->getWebPath();
        // clean up the file property as you won't need it anymore
        //$this->file = null;
    }
}
