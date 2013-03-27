<?php
/*
* Класс для связи полей из базы данных и полей класса.
* Ругается когда обращаются к неопределенному полю.
*/
class data_object{
	/*
	* При вызове неопределенного метода заканчивает работу скрита
	*/
	final public function __call($method, $args){
		die('Method '.$method.' not exists!');
	}
	/*
	* При запросе к неопределенному свойству заканчивает работу скрита
	*/
	final public function __get($property){
		die('Property $'.$property.' not exist!');
	}
	/*
	* При проверке существования неопределенного поля заканчивает работу скрита
	*/	
	final public function __isset($property){
		die('Property $'.$property.' not exist!');
	}
	/*
	* При определении неопределенного поля заканчивает работу скрита
	*/
	final public function __set($property, $value){
		die('Property $'.$property.' not exist!');
	}
	/*
	* При удалении неопределенного поля заканчивает работу скрита
	*/
	final public function __unset($property){
		die('Property $'.$property.' not exist!');
	}	
}