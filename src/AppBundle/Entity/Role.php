<?PHP
namespace AppBundle\Entity;

class Role
{
	protected $roles = array('USER', 'PRESENTER', 'MANAGER', 'ADMIN');

	public function getId($type) {
		return array_search($type, $this->roles);
	}

	public function getKey($id) {
		return 'ROLE_'.$this->roles[$id];
	}
}