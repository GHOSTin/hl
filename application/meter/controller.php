<?php
class controller_meter{

  public static function private_add_period(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->add_period($request->GET('id'), $request->GET('period'))];
  }

    public static function private_add_service(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->add_service($_GET['id'], $_GET['service'])];
    }

    public static function private_create_meter(model_request $request){
        $model = new model_meter(model_session::get_company());
        $model->create_meter($_GET['name'], $_GET['capacity'], $_GET['rates']);
        return ['meters' => $model->get_meters(new data_meter())];
    }

  public static function private_get_dialog_add_period(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('id'))];
  }

  public static function private_get_dialog_add_service(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('id'))];
  }

  public static function private_get_dialog_create_meter(model_request $request){
      return true;
  }

  public static function private_get_dialog_edit_capacity(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('id'))];
  }

  public static function private_get_dialog_edit_rates(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('id'))];
  }

  public static function private_get_dialog_remove_period(model_request $request){
    return true;
  }

  public static function private_get_dialog_remove_service(model_request $request){
    return true;
  }

  public static function private_get_dialog_rename_meter(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('id'))];
  }

  public static function private_get_meter_content(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('id'))];
  }

  public static function private_remove_period(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->remove_period($request->GET('id'), $request->GET('period'))];
  }

    public static function private_remove_service(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->remove_service($_GET['id'], $_GET['service'])];
    }

  public static function private_rename_meter(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->rename_meter($request->GET('id'), $request->GET('name'))];
  }

  public static function private_show_default_page(model_request $request){
    return ['meters' => (new model_meter(model_session::get_company()))
      ->get_meters(new data_meter())];
  }

  public static function private_update_capacity(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->update_capacity($request->GET('id'), $request->GET('capacity'))];
  }

  public static function private_update_rates(model_request $request){
    return ['meter' => (new model_meter(model_session::get_company()))
      ->update_rates($request->GET('id'), $request->GET('rates'))];
  }
}