<?php
set_time_limit(0);
# подключаем фреймворк
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/test'))));
require_once(ROOT."/framework/framework.php");
/*
* Строит объект пользователя от которого будет проходить инифиализация.
*/
function build_current_user($xml){
	try{
		$user = $xml->users->user[0]->attributes();
		$current_user = new data_user();
		$current_user->id = 1;
		$current_user->company_id = 1;
		$current_user->status = true;
		$current_user->login = (string) $user->login;
		$current_user->login = (string) $user->password;
		$current_user->firstname = (string) $user->firstname;
		$current_user->lastname = (string) $user->lastname;
		$current_user->middlename = (string) $user->middlename;
		$current_user->telephone = (string) $user->telephone;
		$current_user->cellphone = (string) $user->cellphone;
		return $current_user;
	}catch(exception $e){
		throw new exception('Проблемы при постройки текущего пользователя.');
	}
}
function create_cities($xml, $current_user){
	if(count($xml->cities->city) < 1)
		throw new exception('Not city');
	foreach($xml->cities->city as $city_node){
		$city = $city_node->attributes();
		$new_city = new data_city();
		$new_city->name = (string) $city->name;
		$new_city->status = (string) $city->status;
		$city = model_city::create_city($new_city, $current_user);
		if($city === false)
				throw new exception('Проблема при создании города.');
		create_streets($city_node, $city, $current_user);
	}
}
/*
* Создает данные в привязке к компаниям.
*/
function create_companies($xml, $current_user){
	if(count($xml->companies->company) < 1)
			throw new exception('Нет компаний');
	foreach($xml->companies->company as $company_node){
		$company = $company_node->attributes();
		$new_company = new data_company();
		$new_company->name = (string) $company->name;
		$new_company->status = (string) $company->status;
		$company = model_company::create_company($new_company, $current_user);
		if($company === false)
			throw new exception('Проблема при создании компании.');
	}
}
function create_flats($house_node, $city, $house, $current_user){
	if(count($house_node->flat) > 0){
		foreach($house_node->flat as $flat_node){
			$flat = $flat_node->attributes();
			$new_flat = new data_flat();
			$new_flat->status = (string) $flat->status;
			$new_flat->number = (string) $flat->number;
			$flat = model_flat::create_flat($house, $new_flat, $current_user);
			if($flat === false)
				throw new exception('Проблема при создании квартиры.');
			create_numbers($flat_node, $city, $flat, $current_user);
		}
	}

}
function create_houses($street_node, $city, $street, $current_user){
	if(count($street_node->house) > 0){
		foreach($street_node->house as $house_node){
			$house = $house_node->attributes();
			$new_house = new data_house();
			$new_house->number = (string) $house->number;
			$new_house->status = (string) $house->status;
			$new_house->department_id = (int) $house->department_id;
			$house = model_house::create_house($street, $new_house, $current_user);
			if($house === false)
				throw new exception('Проблема при создании дома.');
			create_flats($house_node, $city, $house, $current_user);
		}
	}	
}
function create_numbers($flat_node, $city, $flat, $current_user){
	if(count($flat_node->number) > 0){
		foreach($flat_node->number as $number_node){
			$number = $number_node->attributes();
			$new_number = new data_number();
			$new_number->number = (string) $number->number;
			$new_number->password = (string) $number->password;
			$new_number->fio = (string) $number->fio;
			$new_number->status = (string) $number->status;
			$new_number->telephone = (string) $number->telephone;
			$new_number->cellphone = (string) $number->cellphone;
			$new_number->contact_fio = (string) $number->contact_fio;
			$new_number->contact_telephone = (string) $number->contact_telephone;
			$new_number->contact_cellphone = (string) $number->contact_cellphone;
			$number = model_number::create_number($city, $flat, $new_number, $current_user);
			if($number === false)
				throw new exception('Проблема при создании лицевого счета.');
		}
	}
}
function create_streets($city_node, $city, $current_user){
	if(count($city_node->street) > 0){
		foreach($city_node->street as $street_node){
			$street = $street_node->attributes();
			$new_street = new data_street();
			$new_street->name = (string) $street->name;
			$new_street->status = (string) $street->status;
			$street = model_street::create_street($city, $new_street, $current_user);
			if($street === false)
				throw new exception('Проблема при создании улицы.');
			create_houses($street_node, $city, $street, $current_user);
		}
	}
}
/*
* Создает таблицы
*/
function create_tables(){
	$stm = db::get_handler()->exec(file_get_contents(ROOT."/specifications/database_structure.sql"));
}

/*
* Создает фейковых юзеров
*/
function create_users($xml, $current_user){
	if(count($xml->users->user) < 1){
		throw new exception('В конфигурации не перечислены пользователи.');
	}else{
		foreach($xml->users->user as $user){
			$user_attr = $user->attributes();
			$new_user = new data_user();
			$new_user->login = $user_attr->login;
			$new_user->firstname = $user_attr->firstname;
			$new_user->lastname = $user_attr->lastname;
			$new_user->middlename = $user_attr->middlename;
			$new_user->password = $user_attr->password;
			$new_user->telephone = $user_attr->telephone;
			$new_user->cellphone = $user_attr->cellphone;
			if(model_user::create_user($new_user, $current_user) === false)
				throw new exception('Проблема при создании пользователей.');
		}
	}
}
/*
* Стираем все таблицы в базе данных
*/
function drop_tables(){
	$stm = db::get_handler()->query('SHOW TABLES');
	$stm->setFetchMode(PDO::FETCH_NUM);
	$table_string = '';
	while($table = $stm->fetch())
		$table_string .= $table[0].', ';
	if(!empty($table_string))
		$stm = db::get_handler()->exec('DROP TABLES '.substr($table_string, 0, -2));
}
/*
* Парсинг xml
*/
function parse_xml(){
	$xml = simplexml_load_file(ROOT.'/specifications/data.xml');
	if($xml === false) die('XML ошибка!');
	return $xml;
}
/*
* Запуск
*/
try{
	$xml = parse_xml();
	model_environment::create_batabase_connection();
	drop_tables();
	create_tables();
	$current_user = build_current_user($xml);
	create_users($xml, $current_user);
	create_companies($xml, $current_user);
	create_cities($xml, $current_user);
	print 'Установка прошла успешно.'.PHP_EOL;
}catch(exception $e){
	die($e->getMessage().PHP_EOL);
}