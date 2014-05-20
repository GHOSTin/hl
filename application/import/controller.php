<?php
class controller_import{

	public static function private_show_default_page(model_request $request){
		return true;
	}

  public static function private_get_dialog_import_accruals(
      model_request $request){
      return true;
  }

  public static function private_get_dialog_import_statements(
      model_request $request){
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
    di::get('model_import')
      ->load_numbers($request->POST('city_id'), $request->POST('street_id'),
      $request->POST('house_id'), $request->POST('numbers'));
    return true;
  }

  public static function private_load_flats(model_request $request){
    $city = new data_city();
    $city->set_id($request->POST('city_id'));
    $street = (new model_city2street($city))
      ->get_street($request->POST('street_id'));
    $house = (new model_street2house($street))
      ->get_house($request->POST('house_id'));
    $flats = $_POST['flats'];
    di::get('model_import')->load_flats($house, $request->POST('flats'));
    return true;
  }

  public static function private_import_numbers(model_request $request){
    return di::get('model_import')
      ->analize_import_numbers($request->FILES('file'));
  }

  public static function private_import_meters(model_request $request){
    return di::get('model_import')
      ->analize_import_meters($request->FILES('file'));
  }

  public static function private_import_accruals(model_request $request){
    try{
      $file = $request->FILES('file')['tmp_name'];
      di::get('model_import')
        ->analize_import_accruals($request->GET('date'), $file);
      return 'Успешно';
    }catch(Exception $e){
      return 'Проблема';
    }
  }

  public static function private_import_statements(model_request $request){
    try{
      $file = $request->FILES('file')['tmp_name'];
      di::get('model_import')
        ->analize_import_statements($request->GET('date'), $file);
      return 'Успешно';
    }catch(Exception $e){
      return 'Проблема';
    }
  }

  public static function private_analize_street(model_request $request){
    return di::get('model_import')
      ->analize_import_street($request->FILES('file'));
  }

  public static function private_analize_house(model_request $request){
    return di::get('model_import')
      ->analize_import_house($request->FILES('file'));
  }

  public static function private_analize_flats(model_request $request){
    return di::get('model_import')
    ->analize_import_flats($request->FILES('file'));
  }
}