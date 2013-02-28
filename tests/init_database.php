<?php
set_time_limit(0);
# подключаем фреймворк
$dir = dirname(__FILE__);
define('ROOT' , substr($dir, 0, (strlen($dir) - strlen('/test'))));
require_once(ROOT."/framework/framework.php");
/*
* Создает таблицы
*/
function create_tables(){
	$stm = db::get_handler()->exec(file_get_contents(ROOT."/specifications/database_structure.sql"));
}
/*
* Создает города
*/
function create_cities(){
	$cities = [
		[1, 1, true, 'Первоуральск'],
		[2, 1, true, 'Ревда']
	];
	$sql = "INSERT INTO `cities` (
				`id`, `company_id`, `status`, `name`
			) VALUES (
				:city_id, :company_id, :status, :name 
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindParam(':city_id', $city_id);
	$stm->bindParam(':company_id', $company_id);
	$stm->bindParam(':status', $status);
	$stm->bindParam(':name', $name);
	foreach($cities as $city){
		list($city_id, $company_id, $status, $name) = $city;
		$stm->execute();
		$stm->closeCursor();
	}
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
function create_flats(){
	$flats = [
		[1, 1, 1, true, 1],
		[2, 1, 1, true, 2],
		[3, 1, 1, true, 3],
		[4, 1, 1, true, 4],
		[5, 1, 1, true, 5],
		[6, 1, 1, true, 6],
		[7, 1, 1, true, 7],
		[8, 1, 1, true, 8],
		[9, 1, 1, true, 9],
		[10, 1, 1, true, 10]
	];
	$sql = "INSERT INTO `flats` (
				`id`, `company_id`, `house_id`, `status`, `flatnumber`
			) VALUES (
				:flat_id, :company_id, :house_id, :status, :number 
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindParam(':flat_id', $flat_id);
	$stm->bindParam(':company_id', $company_id);
	$stm->bindParam(':house_id', $house_id);
	$stm->bindParam(':status', $status);
	$stm->bindParam(':number', $number);
	foreach($flats as $flat){
		list($flat_id, $company_id, $house_id, $status, $number) = $flat;
		$stm->execute();
		$stm->closeCursor();
	}
}
/*
* Создает улицы
*/
function create_houses(){
	$houses = [
		[1, 1, 1, 1, 1, true, '32б'],
		[2, 1, 1, 1, 1, true, '49'],
		[3, 1, 1, 1, 1, true, '52'],
		[4, 1, 1, 1, 1, true, '16'],
		[5, 1, 1, 1, 1, true, '17'],
		[6, 1, 1, 1, 1, true, '18'],
		[7, 1, 1, 2, 2, true, '18'],
		[8, 1, 1, 2, 2, true, '21'],
		[9, 1, 1, 3, 3, true, '37'],
		[10, 1, 1, 4, 3, true, '78'],
		[11, 1, 1, 5, 3, true, '11']
	];
	$sql = "INSERT INTO `houses` (
				`id`, `company_id`, `city_id`, `street_id`, `department_id`,
				`status`, `housenumber`
			) VALUES (
				:house_id, :company_id, :city_id, :street_id, :department_id,
				:status, :number
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindParam(':house_id', $house_id);
	$stm->bindParam(':company_id', $company_id);
	$stm->bindParam(':city_id', $city_id);
	$stm->bindParam(':street_id', $street_id);
	$stm->bindParam(':department_id', $department_id);
	$stm->bindParam(':status', $status);
	$stm->bindParam(':number', $number);
	foreach($houses as $house){
		list($house_id, $company_id, $city_id, $street_id, $department_id,
			$status, $number) = $house;
		$stm->execute();
		$stm->closeCursor();
	}
}
/*
* Создает лицевые счета
*/
// CREATE TABLE IF NOT EXISTS `numbers` (
//   `id` MEDIUMINT(8) UNSIGNED NOT NULL,
//   `company_id` TINYINT(3) UNSIGNED NOT NULL,
//   `city_id` SMALLINT(5) UNSIGNED NOT NULL,
//   `house_id` SMALLINT(5) UNSIGNED NOT NULL,
//   `flat_id` MEDIUMINT(8) UNSIGNED NOT NULL,
//   `number` VARCHAR(20) NOT NULL,
//   `type` ENUM('human','organization','house') NOT NULL,
//   `status` ENUM('true','false') NOT NULL DEFAULT 'true',
//   `fio` VARCHAR(255) DEFAULT NULL,
//   `telephone` VARCHAR(11) DEFAULT NULL,
//   `cellphone` VARCHAR(11) DEFAULT NULL,
//   `password` VARCHAR(255) NOT NULL,
//   `contact-fio` VARCHAR(255) DEFAULT NULL,
//   `contact-telephone` VARCHAR(11) DEFAULT NULL,
//   `contact-cellphone` VARCHAR(11) DEFAULT NULL,
//   PRIMARY KEY (`id`),
//   KEY `number` (`number`),
//   KEY `password` (`password`),
//   KEY `company_id` (`company_id`),
//   KEY `flat_id` (`flat_id`),
//   KEY `type` (`type`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
function create_numbers(){
	$numbers = [
		[1, 1, 1, 1, 1, 546789, 'human', true, 'Некрасов Евгений Валерьевич',
		'647957', '89222944742', 'password', 'contact_fio', 'con_telephone', 'con_cellphone'],
		[2, 1, 1, 1, 1, 546790, 'human', true, 'Некрасов Евгений Валерьевич',
		'647957', '89222944742', 'password', 'contact_fio', 'con_telephone', 'con_cellphone'],
		[3, 1, 1, 1, 2, 546689, 'human', true, 'Некрасов Евгений Валерьевич',
		'647957', '89222944742', 'password', 'contact_fio', 'con_telephone', 'con_cellphone']
	];
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
	$stm->bindParam(':number_id', $number_id);
	$stm->bindParam(':company_id', $company_id);
	$stm->bindParam(':city_id', $city_id);
	$stm->bindParam(':house_id', $house_id);
	$stm->bindParam(':flat_id', $flat_id);
	$stm->bindParam(':number', $number);
	$stm->bindParam(':type', $type);
	$stm->bindParam(':status', $status);
	$stm->bindParam(':fio', $fio);
	$stm->bindParam(':telephone', $telephone);
	$stm->bindParam(':cellphone', $cellphone);
	$stm->bindParam(':password', $password);
	$stm->bindParam(':contact_fio', $contact_fio);
	$stm->bindParam(':contact_telephone', $contact_telephone);
	$stm->bindParam(':contact_cellphone', $contact_cellphone);
	foreach($numbers as $numberData){
		// var_dump($number);exit();
		list($number_id, $company_id, $city_id, $house_id, $flat_id, $number,
			$type, $status, $fio, $telephone, $cellphone, $password, 
			$contact_fio, $contact_telephone, $contact_cellphone) = $numberData;
		$stm->execute();
		$stm->closeCursor();
	}
}
/*
* Создает улицы
*/
function create_streets(){
	$streets = [
		[1, 1, 1, true, 'Ватутина ул'],
		[2, 1, 1, true, 'Емлина ул'],
		[3, 1, 1, true, 'Строителей ул'],
		[4, 1, 1, true, 'Ленина ул'],
		[5, 1, 1, true, 'Чекистов ул']
	];
	$sql = "INSERT INTO `streets` (
				`id`, `company_id`, `city_id`, `status`, `name`
			) VALUES (
				:street_id, :company_id, :city_id, :status, :name 
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindParam(':street_id', $street_id);
	$stm->bindParam(':company_id', $company_id);
	$stm->bindParam(':city_id', $city_id);
	$stm->bindParam(':status', $status);
	$stm->bindParam(':name', $name);
	foreach($streets as $street){
		list($street_id, $company_id, $city_id, $status, $name) = $street;
		$stm->execute();
		$stm->closeCursor();
	}
}
/*
* Создает фейковых юзеров
*/
function create_users(){
	$users = [
		[1, 1, true, 'NekrasovEV', 'Евгений', 'Некрасов', 'Валерьевич', '{htyfntym',
		'647957', '89222944742'],
		[2, 1, true, 'test', 'Firstname', 'Lastname', 'Middlename', 'test',
		'123456', '8123456789']
	];
	$sql = "INSERT INTO `users` (
				`id`, `company_id`, `status`, `username`, `firstname`, `lastname`,
				`midlename`, `password`, `telephone`, `cellphone`
			) VALUES (
				:user_id, :company_id, :status, :login, :firstname, :lastname, 
				:middlename, :password, :telephone, :cellphone
			);";
	$stm = db::get_handler()->prepare($sql);
	$stm->bindParam(':user_id', $user_id);
	$stm->bindParam(':company_id', $company_id);
	$stm->bindParam(':status', $status);
	$stm->bindParam(':login', $login);
	$stm->bindParam(':firstname', $firstname);
	$stm->bindParam(':lastname', $lastname);
	$stm->bindParam(':middlename', $middlename);
	$stm->bindParam(':password', $password);
	$stm->bindParam(':telephone', $telephone);
	$stm->bindParam(':cellphone', $cellphone);
	foreach($users as $user){
		list($user_id, $company_id, $status, $login, $firstname, $lastname,
			$middlename, $password, $telephone, $cellphone) = $user;
		$password = get_password_hash($password);
		$stm->execute();
		$stm->closeCursor();
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
	model_environment::create_batabase_connection();
	drop_tables();
	create_tables();
	create_users();
	create_cities();
	create_streets();
	create_houses();
	create_departments();
	create_flats();
	create_numbers();
}catch(exception $e){
	die($e->getMessage());
}