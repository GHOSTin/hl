<?php

use \Pimple\Container;

class model_number{

	private $pimple;

	public function __construct(Container $pimple){
		$this->pimple = $pimple;
	}

	public function get_houses_by_street($street_id){
		$houses = $this->pimple['em']->getRepository('data_house')
									 ->findBy(['street' => $street_id]);
		natsort($houses);
		return $houses;
	}
}