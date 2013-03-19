<?php
class model_menu{
	public static function build_hot_menu($component, $controller){
		try{
			if(property_exists($controller, 'name')){
				$hot_menu = [$component => $controller::$name];
				$_SESSION['hot_menu'] = (array_merge((array)$_SESSION['hot_menu'], $hot_menu));
			}
		}catch(exception $e){
			throw new exception('Проблема при формировании hot_menu.');
		}
	}
	public static function get_hot_menu($component, $controller){
		try{
			if(property_exists($controller, 'name')){
				$hot_menu = [$component => $controller::$name];
				$_SESSION['hot_menu'] = (array_merge((array)$_SESSION['hot_menu'], $hot_menu));
			}
		}catch(exception $e){
			throw new exception('Проблема при формировании hot_menu.');
		}
	}
}