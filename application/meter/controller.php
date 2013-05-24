<?php
class controller_meter{

    public static function private_get_dialog_create_meter(){
        return true;
    }

    public static function private_show_default_page(){
        return ['meters' => model_meter::get_meters(model_session::get_company(), new data_meter())];
    }
}