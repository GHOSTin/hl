<?php
class application_router{

	static private $componentName;
	static private $componentMethod;

	static public function build_route(){
		
		if(empty(self::$componentName)){
				
			self::set_component_name(http_router::get_component_name());
			self::set_component_method(framework_configuration::publicMethodPrefix.http_router::get_component_method());
		}
		
		if(empty(self::$componentMethod))
			self::set_component_method(framework_configuration::publicMethodPrefix.http_router::get_component_name());
	}
	
	static public function get_component_method(){
	
		if(empty(self::$componentName) OR empty(self::$componentMethod)) self::build_route();
		return self::$componentMethod;
	}	
	
	static public function get_component_name(){
		
		if(empty(self::$componentName)) self::build_route();
		return self::$componentName;
	}
	
	static public function set_component_method($componentMethod){
	
		self::$componentMethod = $componentMethod;
	}	
	
	static public function set_component_name($componentName){
	
		self::$componentName = $componentName;
	}	
}
?>