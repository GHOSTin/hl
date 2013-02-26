<?php
# подключаем фреймворк
$dir = dirname(__FILE__);
define('ROOT' , substr($dir, 0, (strlen($dir) - strlen('/test'))));
require_once(ROOT."/framework/framework.php");
try{
	model_environment::create_batabase_connection();
	drop_tables();
	create_tables();
	
}catch(exception $e){

	die($e->getMessage());
}

function create_tables(){
	$stm = db::get_handler()->exec(file_get_contents(ROOT."/specifications/database_structure.sql"));
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

	
