<?php 
set_time_limit(0);
define('ROOT' , dirname(__FILE__));
require_once(ROOT."/framework/framework.php");
try{
	mvc::get_model('environment.create_database_connection', new information(false));
	$sql = "CREATE TABLE IF NOT EXISTS `ctps` (
				`ctp_id` SMALLINT(5) UNSIGNED NOT NULL,
				`ctp_city_id` TINYINT(3) UNSIGNED NOT NULL,
				`ctp_name` VARCHAR(255) NOT NULL,
				UNIQUE KEY `indx_id` (`ctp_city_id`, `ctp_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$ctl = environment::get('database')->execute($sql);
	if($ctl->get_error())
		throw new exception('Failure create ctps table');
	print("Create table ctps - OK\n");
}catch(exception $e){
	die($e->getMessage());
}