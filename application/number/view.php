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

    public static function private_get_meters($args){
        return load_template('number.private_get_meters', $args);
    }
}