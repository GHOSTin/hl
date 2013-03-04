<?php
$city_id = 0;
$street_id = 0;
$house_id = 0;
$flat_id = 0;
$number_id = 0;
set_time_limit(0);
# подключаем фреймворк
$dir = dirname(__FILE__);
define('ROOT' , substr($dir, 0, (strlen($dir) - strlen('/test'))));
require_once(ROOT."/framework/framework.php");

/*
* Парсинг xml
*/
function parse_xml(){
	$xml = simplexml_load_file(ROOT.'/specifications/data.xml');
	if($xml === false) die('XML ошибка!');
	return $xml;
}
/*
* Создает таблицы
*/
function create_tables(){
	$stm = db::get_handler()->exec(file_get_contents(ROOT."/specifications/database_structure.sql"));
}
/*
* Создает участки
*/
function create_departments(){
	$departments = [
		[1, 1, true, 'Центральный'],
		[2, 1, true, 'Первый'],
		[3, 1, true, 'Второй']
	];
	$sql = "INSERT INTO `departments` (
				`id`, `company_id`, `status`, `name`
			) VALUES (
				:department_id, :company_id, :status, :name 
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindParam(':department_id', $department_id);
	$stm->bindParam(':company_id', $company_id);
	$stm->bindParam(':status', $status);
	$stm->bindParam(':name', $name);
	foreach($departments as $department){
		list($department_id, $company_id, $status, $name) = $department;
		$stm->execute();
		$stm->closeCursor();
	}
}
function load_ls($xml, $current_user){
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
		if(count($city_node->street) > 0){
			foreach($city_node->street as $street_node){
				$street = $street_node->attributes();
				$new_street = new data_street();
				$new_street->name = (string) $street->name;
				$new_street->status = (string) $street->status;
				$street = model_street::create_street($city, $new_street, $current_user);
				if($street === false)
					throw new exception('Проблема при создании улицы.');
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
						if(count($house_node->flat) > 0){
							foreach($house_node->flat as $flat_node){
								$flat = $flat_node->attributes();
								$new_flat = new data_flat();
								$new_flat->status = (string) $flat->status;
								$new_flat->number = (string) $flat->number;
								$flat = model_flat::create_flat($house, $new_flat, $current_user);
								if($flat === false)
									throw new exception('Проблема при создании квартиры.');
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
						}
					}
				}
			}
		}
	}
}
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
function get_password_hash($passwd){
	return md5(md5(htmlspecialchars($passwd)).application_configuration::authSalt);
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
	load_ls($xml, $current_user);
}catch(exception $e){
	die($e->getMessage());
}