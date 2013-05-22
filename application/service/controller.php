<?php
class controller_service{

    public static function private_show_default_page(){
        return ['services' => model_service::get_services(model_session::get_company(), new data_service())];
    }

    public static function private_get_dialog_create_service(){
        return true;
    }

    public static function private_create_service(){
        $service = new data_service();
        $service->name = $_GET['name'];
        model_service::create_service(model_session::get_company(), $service);
        return ['services' => model_service::get_services(model_session::get_company(), new data_service())];
    }
}