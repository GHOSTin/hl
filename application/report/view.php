<?php
class view_report{

    public static function private_get_query_reports($args){
        return load_template('report.private_get_query_reports', $args);
    }

    public static function private_report_query_one($args){
        return load_template('report.private_report_query_one', $args);
    }

    public static function private_set_time_begin($args){
        return load_template('report.empty_ajax_answer', $args);
    }

    public static function private_set_time_end($args){
        return load_template('report.empty_ajax_answer', $args);
    }

    public static function private_set_filter_query_department($args){
        return load_template('report.empty_ajax_answer', $args);
    }

    public static function private_set_filter_query_status($args){
        return load_template('report.empty_ajax_answer', $args);
    }

    public static function private_set_filter_query_street($args){
        return load_template('report.private_set_filter_query_street', $args);
    }

    public static function private_set_filter_query_house($args){
        return load_template('report.empty_ajax_answer', $args);
    }

    public static function private_set_filter_query_worktype($args){
        return load_template('report.empty_ajax_answer', $args);
    }

	public static function private_show_default_page($args){
		return load_template('report.private_show_default_page', $args);
	}
}