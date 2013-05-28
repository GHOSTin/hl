<?php
class controller_meter{

    public static function private_add_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        model_meter::verify_id($meter);
        $service = new data_service();
        $service->id = $_GET['service_id'];
        $company =  model_session::get_company();
        model_meter::add_service($company, $meter, $service);
        return ['meter' => $meter, 
                'services' => model_meter::get_services(model_session::get_company(), $meter, new data_service())];
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
        $company = model_session::get_company();
        return ['meters' => model_meter::get_meters($company, $meter),
                'services' => model_service::get_services($company, new data_service())];
    }

    public static function private_get_dialog_create_meter(){
        return true;
    }

    public static function private_get_dialog_remove_service(){
        $meter = new data_meter();
        $meter->id = $_GET['meter_id'];
        model_meter::verify_id($meter);
        $service = new data_service();
        $service->id = $_GET['service_id'];
        model_service::verify_id($service);
        $company = model_session::get_company();
        return ['meters' => model_meter::get_meters($company, $meter),
                'services' => model_service::get_services($company, $service)];
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
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter),
                'services' => model_meter::get_services(model_session::get_company(), $meter, new data_service())];
    }

    public static function private_remove_service(){
        $meter = new data_meter();
        $meter->id = $_GET['meter_id'];
        $service = new data_service();
        $service->id = $_GET['service_id'];
        $company = model_session::get_company();
        var_dump(model_meter::remove_service($company, $meter, $service));
        exit();
        return ['meters' => model_meter::get_meters($company, $meter),
                'services' => model_service::get_services($company, $service)];
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
}