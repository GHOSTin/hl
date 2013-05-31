<?php
class view_meter{

    public static function private_add_period($args){
        return load_template('meter.private_add_period', $args);
    }

    public static function private_add_service($args){
        return load_template('meter.private_add_service', $args);
    }

    public static function private_create_meter($args){
        return load_template('meter.private_create_meter', $args);
    }

    public static function private_get_dialog_add_period($args){
        return load_template('meter.private_get_dialog_add_period', $args);
    }

    public static function private_get_dialog_add_service($args){
        return load_template('meter.private_get_dialog_add_service', $args);
    }

    public static function private_get_dialog_create_meter($args){
        return load_template('meter.private_get_dialog_create_meter', $args);
    }

    public static function private_get_dialog_edit_capacity($args){
        return load_template('meter.private_get_dialog_edit_capacity', $args);
    }

    public static function private_get_dialog_edit_rates($args){
        return load_template('meter.private_get_dialog_edit_rates', $args);
    }

    public static function private_get_dialog_remove_period($args){
        return load_template('meter.private_get_dialog_remove_period', $args);
    }

    public static function private_get_dialog_remove_service($args){
        return load_template('meter.private_get_dialog_remove_service', $args);
    }

    public static function private_get_dialog_rename_meter($args){
        return load_template('meter.private_get_dialog_rename_meter', $args);
    }

    public static function private_get_meter_content($args){
        return load_template('meter.private_get_meter_content', $args);
    }

    public static function private_remove_period($args){
        return load_template('meter.private_remove_period', $args);
    }

    public static function private_remove_service($args){
        return load_template('meter.private_remove_service', $args);
    }

    public static function private_rename_meter($args){
        return load_template('meter.private_rename_meter', $args);
    }

    public static function private_show_default_page($args){
        return load_template('meter.private_show_default_page', $args);
    }

    public static function private_update_capacity($args){
        return load_template('meter.private_update_capacity', $args);
    }

    public static function private_update_rates($args){
        return load_template('meter.private_update_rates', $args);
    }
}