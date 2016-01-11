<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Manager extends User
{
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern = "/^\S+/",
     *     message = "Данное поле должно содержать одно слово - фамилию представителя."
     * )
     * @Assert\NotBlank(message = "У представителя обязательно должна быть указана фамилия.")
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern = "/^\S+/",
     *     message = "Данное поле должно содержать одно слово - имя представителя."
     * )
     * @Assert\NotBlank(message = "У представителя обязательно должно быть указано имя.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern = "/^\S+/",
     *     message = "Данное поле должно содержать одно слово - отчество представителя."
     * )
     * @Assert\NotBlank(message = "У представителя обязательно должно быть указано отчество.")
     */
    private $patronymic;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Choice(
     *     choices = {NULL, "m", "f"},
     *     message = "Выберите пол"
     * )
     */
    private $gender;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date(
     *     message = "Если вы хотите указать дату, то вы должны указать её полностью."
     * )
     */
    private $birthday;

    /**
     * @ORM\ManyToOne(targetEntity="Territory")
     */
    private $territory;

    /**
     * @ORM\OneToMany(targetEntity="Presenter", mappedBy="manager")
     */
    private $presenters;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->presenters = new ArrayCollection();
    }

    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @param mixed $patronymic
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTerritory()
    {
        return $this->territory;
    }

    /**
     * @param mixed $territory
     */
    public function setTerritory($territory)
    {
        $this->territory = $territory;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->surname." ".$this->name." ".$this->patronymic;
    }

    public function getPresenters()
    {
        return $this->presenters;
    }

    public function addPresenter(Presenter $presenter)
    {
        if(!$this->presenters->contains($presenter)){
            $this->presenters->add($presenter);
        }
        return $this;
    }

    public function removePresenter(Presenter $presenter)
    {
        if ($this->presenters->contains($presenter)) {
            $this->presenters->removeElement($presenter);
        }

        return $this;
    }
}
