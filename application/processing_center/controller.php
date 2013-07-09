<?php
class controller_processing_center{

    static $name = 'Единый расчетный центр';

    public static function private_show_default_page(){
        return ['centers' => model_processing_center::get_processing_centers(new data_processing_center())];
    }
}