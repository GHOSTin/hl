<?php
class data_object{
	final public function __call($method, $args){
		die('Method '.$method.' not exists!');
	}
	final public function __get($property){
		die('Property $'.$property.' not exist!');
	} 
	final public function __isset($property){
		die('Property $'.$property.' not exist!');
	}	
	final public function __set($property, $value){
		die('Property $'.$property.' not exist!');
	} 	
	final public function __unset($property){
		die('Property $'.$property.' not exist!');
	}	
}