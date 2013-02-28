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
// CREATE TABLE IF NOT EXISTS `departments` (
//   `id` TINYINT(3) UNSIGNED NOT NULL,
//   `company_id` TINYINT(3) UNSIGNED NOT NULL,
//   `status` ENUM('active','deactive') NOT NULL DEFAULT 'active',
//   `name` VARCHAR(255) NOT NULL,
//   KEY `id` (`company_id`,`id`),
//   KEY `name` (`name`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
function create_departments(){
	$departments = [
		[1, 1, true, 'Центральный'],
		[2, 1, true, 'Первый'],
		[2, 1, true, 'Второй']
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
}catch(exception $e){
	die($e->getMessage());
}