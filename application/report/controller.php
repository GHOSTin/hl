<?php
class controller_report{

	static $name = 'Отчеты';

	public static function private_get_query_reports(model_request $request){
		$model = di::get('model_report_query');
		return null;
	}

	public static function private_show_default_page(model_request $request){
		return null;
	}
}