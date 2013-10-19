<?php
class controller_import{

	public static function private_show_default_page(model_request $request){
		return true;
	}

    public static function private_get_dialog_import_numbers(
        model_request $request){
        return true;
    }

    public static function private_get_dialog_import_meters(
        model_request $request){
        return true;
    }

    public static function private_get_dialog_import_street(
        model_request $request){
        return true;
    }

    public static function private_get_dialog_import_house(
        model_request $request){
        return true;
    }

    public static function private_get_dialog_import_flats(
        model_request $request){
        return true;
    }

    public static function private_create_street(model_request $request){
      $city = (new model_city)->get_city($request->GET('city_id'));
      return ['street' => (new model_city2street($city))
          ->create_street($request->GET('name'))];
    }

    public static function private_create_house(model_request $request){
      $street = (new model_street)->get_street($request->GET('street_id'));
      return ['house' =>(new model_street2house($street))
        ->create_house($request->GET('number'))];
    }

    public static function private_load_numbers(model_request $request){
        $numbers = $_POST['numbers'];
        $city = new data_city();
        $city->id = $_POST['city_id'];
        $street = new data_street();
        $street->id = $_POST['street_id'];
        $house = new data_house();
        $house->id = $_POST['house_id'];
        model_import::load_numbers(model_session::get_company(), $city, $street,
                                    $house, $numbers);
        return true;
    }

    public static function private_load_flats(model_request $request){
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

  public static function private_import_numbers(model_request $request){
    return model_import::analize_import_numbers($_FILES['file']);
  }

  public static function private_analize_street(model_request $request){
    return (new model_import(model_session::get_company()))
      ->analize_import_street($request->FILES('file'));
  }

  public static function private_analize_house(model_request $request){
    return (new model_import)->analize_import_house($request->FILES('file'));
  }

  public static function private_analize_flats(model_request $request){
    return (new model_import)->analize_import_flats($request->FILES('file'));
  }
}