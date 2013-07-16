<?php
class view_report{

    public static function private_get_query_reports($args){
        return load_template('report.private_get_query_reports', $args);
    }  

	public static function private_show_default_page($args){
		return load_template('report.private_show_default_page', $args);
	}
}