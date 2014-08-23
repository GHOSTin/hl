<?php

class model_menu{

	public static function build_menu($component){
		$profiles = ['number', 'query', 'metrics', 'task'];
		foreach($profiles as $profile){
			$controller = 'controller_'.$profile;
			if(property_exists($controller, 'name'))
				$args[] = ['href' => $profile, 'title' => $controller::$name];
		}
		return $args;
	}
}