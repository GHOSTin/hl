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
        return ['number' => model_number::get_number($number),
                'meters' => model_number::get_meters($number, $_SESSION['user'])];
    }

    public static function private_get_number_content(){
        $number = new data_number();
        $number->id = $_GET['id'];
        return model_number::get_number($number);
    }

    public static function private_get_number_information(){
        $number = new data_number();
        $number->id = $_GET['id'];
        return model_number::get_number($number);
    }

    public static function private_get_meter_data(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $meter = new data_meter();
        $meter->id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $time = getdate();
        return [ 'meter' => $meter, 'number' => $number, 'time' => mktime(12, 0, 0, 1, 1, $time['year']), 
                'meter_data' =>model_number::get_meter_data($meter, $number, $_SESSION['user'], mktime(12, 0, 0, 1, 1, $time['year']))];
    }

    public static function private_get_dialog_edit_number(){
        $number = new data_number();
        $number->id = $_GET['id'];
        return model_number::get_number($number);
    }

    public static function private_get_dialog_edit_meter_data(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $meter = new data_meter();
        $meter->id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $time = $_GET['time'];
        return ['number' => $number, 'meter' => $meter,
                'time' => $_GET['time']];
    }

    public static function private_update_number(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->number = $_GET['number'];
        return model_number::update_number($number, $_SESSION['user']);
    }
    public static function private_update_meter_data(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $meter = new data_meter();
        $meter->id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        var_dump(model_number::update_meter_data($meter, $number, $_SESSION['user'], time()));
        exit();
    }
}