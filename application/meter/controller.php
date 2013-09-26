<?php
class controller_meter{

    public static function private_add_period(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->add_period($_GET['id'], $_GET['period'])];
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
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_add_service(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_create_meter(model_request $request){
        return true;
    }

    public static function private_get_dialog_edit_capacity(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_edit_rates(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_remove_period(model_request $request){
        $meter = new data_meter();
        $meter->set_id($_GET['id']);
        $meter->add_period($_GET['period']);
        $meter->verify('id', 'periods');
        return ['meter' => $meter];
    }

    public static function private_get_dialog_remove_service(model_request $request){
        $meter = new data_meter();
        $meter->set_id($_GET['id']);
        $meter->add_service($_GET['service']);
        $meter->verify('id', 'service');
        return ['meter' => $meter];
    }

    public static function private_get_dialog_rename_meter(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_meter_content(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_remove_period(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->remove_period($_GET['id'], $_GET['period'])];
    }

    public static function private_remove_service(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->remove_service($_GET['id'], $_GET['service'])];
    }

    public static function private_rename_meter(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->rename_meter($_GET['id'], $_GET['name'])];
    }

  public static function private_show_default_page(model_request $request){
    return ['meters' => (new model_meter(model_session::get_company()))
      ->get_meters(new data_meter())];
  }

    public static function private_update_capacity(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->update_capacity($_GET['id'], $_GET['capacity'])];
    }

    public static function private_update_rates(model_request $request){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->update_rates($_GET['id'], $_GET['rates'])];
    }
}