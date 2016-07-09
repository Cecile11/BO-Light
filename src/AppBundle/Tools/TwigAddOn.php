<?php
namespace AppBundle\Tools;
use NumberFormatter;
class TwigAddOn extends \Twig_Extension{

	private $security;

	public function __construct($security){
		$this->security = $security;
	}

	public function getName(){
		return 'Obfuscation';
	}

	public function obfData($currentValue){
		if($this->security->getToken()->getUser()->hasRole('ROLE_GUEST')){
			return preg_replace('/(vads_cust_address|vads_cust_email|vads_cust_first_name|vads_cust_last_name|vads_cust_name|vads_ship_to_first_name|vads_ship_to_last_name|vads_ship_to_name|vads_ship_to_phone_num|vads_ship_to_street2?|vads_cust_phone|vads_cust_cell_phone)(":")([^"]*)/ ','$1$2 <i>redacted</i>',$currentValue);
		}
		else{
			return $currentValue;
		}
	}

	public function obfMail($currentValue,$domaine){
		if($this->security->getToken()->getUser()->hasRole('ROLE_GUEST')){
			return substr($currentValue, 0, strpos($currentValue, '@')) . '@' . $domaine;
		}
		else{
			return $currentValue;
		}
	}

	public function getLines($currentValue,$space = ''){
		$nbEspace = -1;
		$string = "";
		$test = false;
		for ($i=0; $i < strlen($currentValue) ; $i++) { 
			$char = $currentValue[$i];
			switch ($char) {
				case '{':
					$nbEspace += 1;
					if ($nbEspace > 0){
						$test = true;
					}
					break;
				case '}':
					$nbEspace -= 1;
					$test = false;
				case '"':
					break;
				case ',':
					$string = $string."<br>";
					for ($j=0; $j < $nbEspace*4 ; $j++) { 
						$string = $string."&nbsp;";
					}
					break;
				default:
					if ($test){
						$string = $string."<br>";
						for ($j=0; $j < $nbEspace*4 ; $j++) { 
							$string = $string."&nbsp;";
						}
						$test = false;
					}
					$string = $string.$char;
			}
		}
		return $string;

	}

	public function getMoney($currency,$value){
		$fmt = numfmt_create( 'fr_FR', NumberFormatter::CURRENCY );
		switch ($currency) {
			case '978':
				numfmt_format_currency($fmt, $value, "EUR");
				break;
			
			default:
				return 0;
				break;
		}
	}

	public function filtreZero($currency){
		if ($currency == "0.00" or $currency == 0){
			return "";
		}else{
			return $currency;
		}
	}

	public function filtreZeroPlus($currency){
			if ($currency == "0.00" or $currency == 0){
			return "";
		}else{
			return '('.((string) $currency).')';
		}
	}

	public function getFilters(){
		return array(
			'obfMail' => new \Twig_SimpleFilter('obfMail', array($this, 'obfMail')),
			'obfData' => new \Twig_SimpleFilter('obfData', array($this, 'obfData')),
			'getLines' => new \Twig_SimpleFilter('getLines', array($this, 'getLines')),
			'filtreZero' => new \Twig_SimpleFilter('filtreZero', array($this, 'filtreZero')),
			'filtreZeroPlus' => new \Twig_SimpleFilter('filtreZeroPlus', array($this, 'filtreZeroPlus'))
		);
	}

	public function getFonction(){
		return array(
			'getMoney' => new \Twig_SimpleFunction('getMoney',array($this,'getMoney'))
			);
	}
} 