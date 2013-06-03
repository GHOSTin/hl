<?php
class controller_meter{

    public static function private_add_period(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->periods[] = $_GET['period'];
        return ['meter' => model_meter::add_period(model_session::get_company(), $meter)];
    }

    public static function private_add_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[] = $_GET['service'];
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

    public static function private_get_dialog_add_period(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->verify(['id']);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_add_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->verify(['id']);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_create_meter(){
        return true;
    }

    public static function private_get_dialog_edit_capacity(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->verify(['id']);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_edit_rates(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->verify(['id']);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_remove_period(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->periods[0] = $_GET['period'];
        $meter->verify(['id', 'periods']);
        return ['meter' => $meter];
    }

    public static function private_get_dialog_remove_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[0] = $_GET['service'];
        $meter->verify(['id', 'service']);
        return ['meter' => $meter];
    }

    public static function private_get_dialog_rename_meter(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->verify(['id']);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_meter_content(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->verify(['id']);
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_remove_period(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->periods[0] = $_GET['period'];
        return ['meter' => model_meter::remove_period(model_session::get_company(), $meter)];
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