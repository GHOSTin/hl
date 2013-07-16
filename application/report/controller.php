<?php
class controller_report{

	static $name = 'Отчеты';

    public static function private_get_query_reports(){
        return true;
    }

	public static function private_show_default_page(){
		return true;
	}
}