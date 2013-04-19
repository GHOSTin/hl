<?php
class controller_number{

	static $name = 'Жилищный фонд';

	public static function private_show_default_page(){
        return ['streets' => model_street::get_streets(new data_street(), $_SESSION['user'])];
	}

    public static function private_get_street_content(){
        $street = new data_street();
        $street->id = $_GET['id'];
        return ['houses' => model_street::get_houses($street),
                'street' => $street];
    }

    public static function private_get_house_content(){
        $house = new data_house();
        $house->id = $_GET['id'];
        return ['numbers' => model_house::get_numbers($house, $_SESSION['user']),
                'house' => $house];
    }

    public static function private_get_meters(){
        $number = new data_number();
        $number->id = $_GET['id'];
        return ['number' => model_number::get_number($number)];
    }

    public static function private_get_number_content(){
        $number = new data_number();
        $number->id = $_GET['id'];
        return model_number::get_number($number);
    }
}