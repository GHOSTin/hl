<?php
class view_service{

    public static function private_show_default_page($args){
        return load_template('service.private_show_default_page', $args);
    }

    public static function private_get_dialog_create_service($args){
        return load_template('service.private_get_dialog_create_service', $args);
    }

    public static function private_create_service($args){
        return load_template('service.private_create_service', $args);
    }
}