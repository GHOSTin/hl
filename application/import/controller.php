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

    public static function private_get_dialog_import_house(){
        return true;
    }

    public static function private_get_dialog_import_flats(){
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

    public static function private_create_house(){
        $house = new data_house();
        $house->number = $_GET['number'];
        $house->status = true;
        $street = new data_street();
        $street->id = $_GET['street_id'];
        return model_street::create_house($street, $house, $_SESSION['user']);
    }

    public static function private_load_numbers(){
        $numbers = $_POST['numbers'];
        $city = new data_city();
        $city->id = $_POST['city_id'];
        $street = new data_street();
        $street->id = $_POST['street_id'];
        $house = new data_house();
        $house->id = $_POST['house_id'];
        model_import::load_numbers(model_session::get_company(), $city, $street,
                                    $house, $numbers);
        exit();

        return true;
    }

    public static function private_load_flats(){
        $flats = $_POST['flats'];
        $city = new data_city();
        $city->id = $_POST['city_id'];
        $street = new data_street();
        $street->id = $_POST['street_id'];
        $house = new data_house();
        $house->id = $_POST['house_id'];
        model_import::load_flats($city, $street, $house, $flats);
        return true;
    }

    public static function private_import_numbers(){
        return model_import::analize_import_numbers($_FILES['file']);
    }

    public static function private_analize_street(){
        return model_import::analize_import_street($_FILES['file']);
    }

    public static function private_analize_house(){
        return model_import::analize_import_house($_FILES['file']);
    }

    public static function private_analize_flats(){
        return model_import::analize_import_flats($_FILES['file']);
    }
}