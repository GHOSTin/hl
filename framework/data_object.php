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
		throw new e_data('Property $'.$method.' not exist!');
	}
	/*
	* При запросе к неопределенному свойству заканчивает работу скрита
	*/
	final public function __get($property){
		throw new e_data('Property $'.$property.' not exist!');
	}
	/*
	* При проверке существования неопределенного поля заканчивает работу скрита
	*/
	final public function __isset($property){
		throw new e_data('Property $'.$property.' not exist!');
	}
	/*
	* При определении неопределенного поля заканчивает работу скрита
	*/
	final public function __set($property, $value){
		throw new e_data('Property $'.$property.' not exist!');
	}
	/*
	* При удалении неопределенного поля заканчивает работу скрита
	*/
	final public function __unset($property){
		throw new e_data('Property $'.$property.' not exist!');
	}
}