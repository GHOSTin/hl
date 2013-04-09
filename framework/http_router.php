<?php
final class http_router{

	static private $component_name;
	static private $method_name;

	static public function build_route(){
		if(isset($_GET[framework_configuration::route_string]))
			$route = htmlspecialchars($_GET[framework_configuration::route_string]);
		if(empty($route)){
			self::set_default_component_name();
			self::set_default_method_name();
		}else{
			$routeArray = explode('.', $route);
			if(empty($routeArray[0])){
				self::set_default_component_name();
				self::set_default_method_name();
			}else{
				self::set_component_name($routeArray[0]);
				if(empty($routeArray[1]))
					self::set_default_method_name();
				else
					self::set_method_name($routeArray[1]);
			}
		}
	}

	static public function get_method_name(){
		if(empty(self::$component_name) OR empty(self::$method_name))
			self::build_route();
		return self::$method_name;
	}

	static public function get_component_name(){
		if(empty(self::$component_name))
			self::build_route();
		return self::$component_name;
	}

	static public function set_method_name($method_name){
		self::$method_name = $method_name;
	}

	static public function set_component_name($component_name){
		self::$component_name = $component_name;
	}

	static private function set_default_component_name(){
		self::set_component_name(framework_configuration::default_component_name);
	}

	static private function set_default_method_name(){
		self::set_method_name(framework_configuration::default_method_name);
	}
}