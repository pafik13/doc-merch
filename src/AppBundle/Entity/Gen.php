<?PHP
namespace AppBundle\Entity;

class Gen
{
	protected $rus_ords = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и',
                                'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т',
                                'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь',
                                'э', 'ю', 'я');

	protected $eng_ords = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i',
								'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't',
								'u', 'f', 'kh', 'ts', 'ch', 'sh', 'shch', '', 'y', '',
								'e', 'yu', 'ya');

	protected function rus_to_eng($string) {
		return str_replace($this->rus_ords, $this->eng_ords, mb_strtolower($string, 'UTF-8'));
	}

	public function genPassword() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        for ($i = 0; $i < 12; $i++) $result .= $characters[rand(0, 61)];
		return $result;
	}

	public function genUsername($surname, $name, $patronymic) {
		return $this->rus_to_eng($surname).$this->rus_to_eng(mb_substr($name, 0, 1, 'UTF-8')).$this->rus_to_eng(mb_substr($patronymic, 0, 1, 'UTF-8'));
	}
}