<?php
 class db{

    private static $connection;

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    public static function pdo(){
    	if(is_null(self::$connection)){
	    	try{
				self::$connection = new PDO('mysql:host='.application_configuration::database_host.';dbname='.application_configuration::database_name, application_configuration::database_user, application_configuration::database_password);
				self::$connection->exec('SET NAMES utf8');
			}catch(exception $e){
				
				die('Database connection error');
			}
		}
		return self::$connection;
    }
 }
?>