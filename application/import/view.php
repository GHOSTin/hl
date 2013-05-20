<?php
class view_import{

	public static function private_show_default_page($args){
		return load_template('import.private_show_default_page', $args);
	}

    public static function private_get_dialog_import_numbers($args){
        return load_template('import.private_get_dialog_import_numbers', $args);
    }

    public static function private_get_dialog_import_meters($args){
        return load_template('import.private_get_dialog_import_meters', $args);
    }

    public static function private_get_dialog_import_street($args){
        return load_template('import.private_get_dialog_import_street', $args);
    }

    public static function private_get_dialog_import_house($args){
        return load_template('import.private_get_dialog_import_house', $args);
    }

    public static function private_get_dialog_import_flats($args){
        return load_template('import.private_get_dialog_import_flats', $args);
    }

    public static function private_import_numbers($args){
        return load_template('import.private_analize_import_numbers', $args);
    }

    public static function private_analize_street($args){
        return load_template('import.private_analize_street', $args);
    }

    public static function private_analize_house($args){
        return load_template('import.private_analize_house', $args);
    }

    public static function private_analize_flats($args){
        return load_template('import.private_analize_flats', $args);
    }

    public static function private_create_street($args){
        return load_template('import.private_create_street', $args);
    }

    public static function private_create_house($args){
        return load_template('import.private_create_house', $args);
    }
}