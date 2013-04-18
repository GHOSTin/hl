<?php
class controller_import{

	public static function private_show_default_page(){
		return true;
	}

    public static function private_get_dialog_import_numbers(){
        return true;
    }

    public static function private_get_dialog_import_meters(){
        return true;
    }

    public static function private_import_numbers(){
        return model_import::analize_import_numbers($_FILES['file']);
    }
}