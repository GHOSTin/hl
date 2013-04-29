<?php
class controller_import{

	public static function private_show_default_page(){
		return true;
	}

    public static function private_get_dialog_import_numbers(){
        return true;
    }

    public static function private_get_dialog_import_meters(){
        return true;
    }

    public static function private_get_dialog_import_street(){
        return true;
    }

    public static function private_create_street(){
        $street = new data_street();
        $street->name = $_GET['name'];
        $street->status = true;
        $city = new data_city();
        $city->id = $_GET['city_id'];
        return model_city::create_street($city, $street, $_SESSION['user']);
    }

    public static function private_load_numbers(){
        $numbers = $_POST['numbers'];
        $city = new data_city();
        $city->id = $_POST['city_id'];
        $street = new data_street();
        $street->id = $_POST['street_id'];
        $house = new data_house();
        $house->id = $_POST['house_id'];
        model_import::load_numbers($city, $street, $house, $numbers, $_SESSION['user']);
        exit();

        return true;
    }

    public static function private_import_numbers(){
        return model_import::analize_import_numbers($_FILES['file']);
    }

    public static function private_analize_street(){
        return model_import::analize_import_street($_FILES['file']);
    }
}