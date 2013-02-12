<?php
 class db{

    private static $connection;
    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    public static function get_instance(){
    	if(is_null(self::$connection))
    		self::connect();
		return self::$connection;
    }
    public static function get_handler(){
    	return self::get_instance();
    }
    public static function connect($host, $database, $user, $password){
    	if(is_null(self::$connection))
			self::$connection = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password);
    }
}
?>