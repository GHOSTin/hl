<?php
class controller_number{

	static $name = 'Жилищный фонд';
	
	public static function private_show_default_page(){
        return ['streets' => model_street::get_streets(new data_street(), $_SESSION['user'])];
	}
}