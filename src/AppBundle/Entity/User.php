<?PHP
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Role;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $username;
	
    /**
     * @ORM\Column(type="string", length=255, unique=true)
	 * @Assert\Email(
     *     message = "E-mail адрес указан некорректно."
	 * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $password;

    /**
     * @ORM\Column(type="integer", length=1)
     */
    private $role;

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
     * @ORM\Column(type="string", length=100)
     */
    private $district;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $manager;

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
	{
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
		$role = new Role();
        return array($role->getKey($this->role));
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set role
     *
     * @param integer $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set patronymic
     *
     * @param string $patronymic
     * @return User
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->surname." ".$this->name." ".$this->patronymic;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set district
     *
     * @param string $district
     * @return User
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string 
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set manager
     *
     * @param integer $manager
     * @return User
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return integer
     */
    public function getManager()
    {
        return $this->manager;
    }
}