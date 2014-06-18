<?php

class model_menu{

	public static function build_menu($component){
		$profiles = ['number', 'query', 'task'];
		foreach($profiles as $profile){
			$controller = 'controller_'.$profile;
			if(property_exists($controller, 'name'))
				$args['menu'][] = ['href' => $profile, 'title' => $controller::$name];
		}
		$args['user'] = di::get('user');
		$args['comp'] = $component;
    return load_template('menu.build_horizontal_menu', $args);
	}
}