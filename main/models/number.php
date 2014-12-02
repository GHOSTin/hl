<?php namespace main\models;

use Silex\Application;

class number{

	private $app;

	public function __construct(Application $app){
		$this->app = $app;
	}

	public function get_houses_by_street($street_id){
		$houses = $this->app['em']->getRepository('\domain\house')
									 						->findByStreet($street_id);
		natsort($houses);
		return $houses;
	}
}