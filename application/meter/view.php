<?php
class view_meter{

    public static function private_create_meter($args){
        return load_template('meter.private_create_meter', $args);
    }

    public static function private_get_dialog_create_meter($args){
        return load_template('meter.private_get_dialog_create_meter', $args);
    }

    public static function private_show_default_page($args){
        return load_template('meter.private_show_default_page', $args);
    }
}