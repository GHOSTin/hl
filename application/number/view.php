<?php
class view_number{

	public static function private_show_default_page($args){
		return load_template('number.private_show_default_page', $args);
	}

    public static function private_get_street_content($args){
        return load_template('number.private_get_street_content', $args);
    }
}