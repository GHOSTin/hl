<?php
class view_import{

	public static function private_show_default_page($args){
		return load_template('import.private_show_default_page', $args);
	}

    public static function private_get_dialog_import_numbers($args){
        return load_template('import.private_get_dialog_import_numbers', $args);
    }
    public static function private_import_numbers($args){
        return load_template('import.private_analize_import_numbers', $args);
    }    
}