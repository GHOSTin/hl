<?php
class controller_meter{

    public static function private_add_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[] = $_GET['service'];
        // var_dump( model_meter::add_service(model_session::get_company(), $meter));
        // exit();
        return ['meter' => model_meter::add_service(model_session::get_company(), $meter)];
    }

    public static function private_create_meter(){
        $meter = new data_meter();
        $meter->name = $_GET['name'];
        $meter->capacity = $_GET['capacity'];
        $meter->rates = $_GET['rates'];
        $company = model_session::get_company();
        model_meter::create_meter($company, $meter);
        return ['meters' => model_meter::get_meters($company, new data_meter())];
    }

    public static function private_get_dialog_add_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        model_meter::verify_id($meter);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_create_meter(){
        return true;
    }

    public static function private_get_dialog_edit_capacity(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        model_meter::verify_id($meter);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_edit_rates(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        model_meter::verify_id($meter);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_remove_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[0] = $_GET['service'];
        model_meter::verify_id($meter);
        model_meter::verify_service($meter);
        return ['meter' => $meter];
    }

    public static function private_get_dialog_rename_meter(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        model_meter::verify_id($meter);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_meter_content(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        model_meter::verify_id($meter);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_remove_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[0] = $_GET['service'];
        return ['meter' => model_meter::remove_service(model_session::get_company(), $meter)];
    }

    public static function private_rename_meter(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->name = $_GET['name'];
        return ['meter' => model_meter::rename_meter(model_session::get_company(), $meter)];
    }

    public static function private_show_default_page(){
        return ['meters' => model_meter::get_meters(model_session::get_company(), new data_meter())];
    }

    public static function private_update_capacity(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->capacity = $_GET['capacity'];
        return ['meter' => model_meter::update_capacity(model_session::get_company(), $meter)];
    }

    public static function private_update_rates(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->rates = $_GET['rates'];
        return ['meter' => model_meter::update_rates(model_session::get_company(), $meter)];
    }
}