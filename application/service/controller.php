<?php
class controller_service{

    public static function private_show_default_page(){
        return ['services' => model_service::get_services(model_session::get_company(), new data_service())];
    }
}