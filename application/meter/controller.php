<?php
class controller_meter{

    public static function private_create_meter(){
        $meter = new data_meter();
        $meter->name = $_GET['name'];
        $meter->capacity = $_GET['capacity'];
        $meter->rates = $_GET['rates'];
        $company = model_session::get_company();
        model_meter::create_meter($company, $meter);
        return ['meters' => model_meter::get_meters($company, new data_meter())];
    }

    public static function private_get_dialog_create_meter(){
        return true;
    }

    public static function private_get_meter_content(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        model_meter::verify_id($meter);
        return ['meters' => model_meter::get_meters(model_session::get_company(), new data_meter())];
    }

    public static function private_show_default_page(){
        return ['meters' => model_meter::get_meters(model_session::get_company(), new data_meter())];
    }
}