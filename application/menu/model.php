<?php
class model_menu{

	public static function build_hot_menu($component, $controller){
		if(property_exists($controller, 'name')){
			$hot_menu = [$component => $controller::$name];
			$_SESSION['hot_menu'] = (array_merge((array)$_SESSION['hot_menu'], $hot_menu));
		}
	}
	
	public static function get_hot_menu($component, $controller){
		if(property_exists($controller, 'name')){
			$hot_menu = [$component => $controller::$name];
			$_SESSION['hot_menu'] = (array_merge((array)$_SESSION['hot_menu'], $hot_menu));
		}
	}
	
	public static function build_menu(){
		// model_menu::build_hot_menu($component, $controller);
		// ['menu' => $_SESSION['menu'], 'hot_menu' => $_SESSION['hot_menu']]
        return view_menu::build_horizontal_menu($args);
	}
}