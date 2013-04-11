<?php
class controller_number{

	static $name = 'Жилищный фонд';

	public static function private_show_default_page(){
        return ['streets' => model_street::get_streets(new data_street(), $_SESSION['user'])];
	}

    public static function private_get_street_content(){
        $street = new data_street();
        $street->id = $_GET['id'];
        return ['houses' => model_street::get_houses($street, $_SESSION['user']),
                'street' => $street];
    }
}