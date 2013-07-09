<?php
class controller_processing_center{

    static $name = 'Единый расчетный центр';

    public static function private_create_processing_center(){
        $center = new data_processing_center();
        $center->name = $_GET['name'];
        model_processing_center::create_processing_center($center);
        return ['centers' => model_processing_center::get_processing_centers(new data_processing_center())];
    }

    public static function private_get_dialog_create_processing_center(){
        return true;
    }

    public static function private_show_default_page(){
        return ['centers' => model_processing_center::get_processing_centers(new data_processing_center())];
    }
}