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
* Создает города
*/
function create_city($city){
	global $city_id;
	$city_id++;
	$sql = "INSERT INTO `cities` (
				`id`, `company_id`, `status`, `name`
			) VALUES (
				:city_id, :company_id, :status, :name 
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindValue(':city_id', $city_id);
	$stm->bindValue(':company_id', 1);
	$stm->bindValue(':status', $city->status);
	$stm->bindValue(':name', $city->name);
	$stm->execute();
	$stm->closeCursor();
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
/*
* Создает квартиры
*/
function create_flat($flat){
	global $house_id, $flat_id;
	$flat_id++;
	$sql = "INSERT INTO `flats` (
				`id`, `company_id`, `house_id`, `status`, `flatnumber`
			) VALUES (
				:flat_id, :company_id, :house_id, :status, :number 
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindValue(':flat_id', $flat_id);
	$stm->bindValue(':company_id', 1);
	$stm->bindValue(':house_id', $house_id);
	$stm->bindValue(':status', $flat->status);
	$stm->bindValue(':number', $flat->number);
	$stm->execute();
	$stm->closeCursor();
}
/*
* Создает улицы
*/

function create_house($house){
	global $city_id, $street_id, $house_id;
	$house_id++;
	$sql = "INSERT INTO `houses` (
				`id`, `company_id`, `city_id`, `street_id`, `department_id`,
				`status`, `housenumber`
			) VALUES (
				:house_id, :company_id, :city_id, :street_id, :department_id,
				:status, :number
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindValue(':house_id', $house_id);
	$stm->bindValue(':company_id', 1);
	$stm->bindValue(':city_id', $city_id);
	$stm->bindValue(':street_id', $street_id);
	$stm->bindValue(':department_id', $house->department_id);
	$stm->bindValue(':status', $house->status);
	$stm->bindValue(':number', $house->number);
	$stm->execute();
	$stm->closeCursor();
}
/*
* Создает лицевые счета
*/
function create_number($number){
	global $city_id, $house_id, $flat_id, $number_id;
	$number_id++;
	$sql = "INSERT INTO `numbers` (
				`id`, `company_id`, `city_id`, `house_id`, `flat_id`, `number`, `type`, `status`,
				`fio`, `telephone`, `cellphone`, `password`, `contact-fio`, `contact-telephone`,
				`contact-cellphone`
			) VALUES (
				:number_id, :company_id, :city_id, :house_id, :flat_id, :number, :type, :status,
				:fio, :telephone, :cellphone, :password, :contact_fio, :contact_telephone,
				:contact_cellphone
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindValue(':number_id', $number_id);
	$stm->bindValue(':company_id', 1);
	$stm->bindValue(':city_id', $city_id);
	$stm->bindValue(':house_id', $house_id);
	$stm->bindValue(':flat_id', $flat_id);
	$stm->bindValue(':number', $number->number);
	$stm->bindValue(':type', 'human');
	$stm->bindValue(':status', $number->status);
	$stm->bindValue(':fio', $number->fio);
	$stm->bindValue(':telephone', $number->telephone);
	$stm->bindValue(':cellphone', $number->cellphone);
	$stm->bindValue(':password', $number->password);
	$stm->bindValue(':contact_fio', $number->contact_fio);
	$stm->bindValue(':contact_telephone', $number->contact_telephone);
	$stm->bindValue(':contact_cellphone', $number->contact_cellphone);
	$stm->execute();
	$stm->closeCursor();
}
/*
* Создает улицу
*/
function create_street($street){
	global $city_id, $street_id;
	$street_id++;
	$sql = "INSERT INTO `streets` (
			`id`, `company_id`, `city_id`, `status`, `name`
		) VALUES (
			:street_id, :company_id, :city_id, :status, :name 
		);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindValue(':street_id', $street_id);
	$stm->bindValue(':company_id', 1);
	$stm->bindValue(':city_id', $city_id);
	$stm->bindValue(':status', $street->status);
	$stm->bindValue(':name', $street->name);
	$stm->execute();
	$stm->closeCursor();
}

function load_ls($xml){
	if(count($xml->cities->city) < 1)
		throw new exception('Not city');
	foreach($xml->cities->city as $city_node){
		$city = $city_node->attributes();
		create_city($city);
		if(count($city_node->street) > 0){
			foreach($city_node->street as $street_node){
				$street = $street_node->attributes();
				create_street($street);
				if(count($street_node->house) > 0){
					foreach($street_node->house as $house_node){
						$house = $house_node->attributes();
						create_house($house);
						if(count($house_node->flat) > 0){
							foreach($house_node->flat as $flat_node){
								$flat = $flat_node->attributes();
								create_flat($flat);
								if(count($flat_node->number) > 0){
									foreach($flat_node->number as $number_node){
										$number = $number_node->attributes();
										create_number($number);
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
		$current_user->login = $user->login;
		$current_user->login = $user->password;
		$current_user->firstname = $user->firstname;
		$current_user->lastname = $user->lastname;
		$current_user->middlename = $user->middlename;
		$current_user->telephone = $user->telephone;
		$current_user->cellphone = $user->cellphone;
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
			$user = $user->attributes();
			$args['login'] = $user->login;
			$args['firstname'] = $user->firstname;
			$args['lastname'] = $user->lastname;
			$args['middlename'] = $user->middlename;
			$args['password'] = $user->password;
			$args['telephone'] = $user->telephone;
			$args['cellphone'] = $user->cellphone;
			if(model_user::create_user($args, $current_user) === false)
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
	exit();
	load_ls($xml);
}catch(exception $e){
	die($e->getMessage());
}