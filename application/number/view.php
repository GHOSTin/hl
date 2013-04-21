<?php
class view_number{

	public static function private_show_default_page($args){
		return load_template('number.private_show_default_page', $args);
	}

    public static function private_get_street_content($args){
        return load_template('number.private_get_street_content', $args);
    }

    public static function private_get_house_content($args){
        return load_template('number.private_get_house_content', $args);
    }

    public static function private_get_number_content($args){
        return load_template('number.private_get_number_content', $args);
    }

    public static function private_get_dialog_edit_number($args){
        return load_template('number.private_get_dialog_edit_number', $args);
    }

    public static function private_get_number_information($args){
        return load_template('number.private_get_number_information', $args);
    }

    public static function private_get_meters($args){
        return load_template('number.private_get_meters', $args);
    }

    public static function private_get_meter_data($args){
        return load_template('number.private_get_meter_data', $args);
    }
    public static function private_get_dialog_edit_meter_data($args){
        return load_template('number.private_get_dialog_edit_meter_data', $args);
    }
}