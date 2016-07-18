<?php
/*
 * Payzen parameters
 * and General Purpose functions
 *
 * Authors :
 *  Parslow, LyraNetwork
 *
 */

// Define URL constant
//  use secure.payzen.eu for production instance
//      demo.payzen.eu for demo instance
// define("NAMESPACE_URL","http://v5.ws.vads.lyra.com/Header/");
// define("WSDL_URL","https://secure.payzen.eu/vads-ws/v5?wsdl"); deplacer dans /app/parametre.yml
// define("WSDL_URL","https://demo.payzen.eu/vads-ws/v5?wsdl");

// LOG prefix - keep spip_log compatibility
namespace CoreBundle\PayzenWSv5;

class Config{

	private $config;
	private $mode;
	private $iniValues;

	public function __construct($requestStack){
		// A changer en mode prod ( possibilité de parser des fichier ect)
		$this->iniValues = parse_ini_file(__DIR__."/../../../app/Resources/key.ini",true);
		$idKey     = array_keys($this->iniValues);
		$id =  array_search($requestStack->getCurrentRequest()->attributes->get('site_id'),array_column($this->iniValues, 'site_id')); // Payzen ShopID
		$this->config['SITE_ID'] = $this->iniValues[$idKey[$id]]['site_id'];		
		$this->config['mode_test'] =  null;  // define if payzen will be called in TEST mode
		$this->config['CLE_test']  =  $this->iniValues[$idKey[$id]]['key_test']; // Payzen TEST key
		$this->config['CLE']       =  isset($this->iniValues[$idKey[$id]]['key_prod']) ? $this->iniValues[$idKey[$id]]['key_prod'] : null;   // Payzen PRODUCTION key
		$this->mode   =  'inter';
	}
	public function getConfig(){
		return $this->config;
	}
	public function getMode(){
		return $this->mode;
	}
	public function getKey(){
		if ($this->config['mode_test'] == "1" ){
			return $this->config['CLE_test'];
		} else {
			return $this->config['CLE'];
		}
	}

	public function getSiteIdList(){
		return array_column($this->iniValues,'site_id');
	}
}



