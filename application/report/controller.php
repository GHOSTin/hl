<?php
class controller_report{

	static $name = 'Отчеты';

	public static function private_get_query_reports(model_request $request){
		$model = di::get('model_report_query');
		return null;
	}

  public static function private_set_time_begin(model_request $request){
    $model = di::get('model_report_query');
    $model->set_time_begin(strtotime($request->GET('time')));
    return null;
  }

  public static function private_set_time_end(model_request $request){
    $model = di::get('model_report_query');
    $model->set_time_end(strtotime($request->GET('time')));
    return null;
  }

  public static function private_report_query_one(model_request $request){
    $model = di::get('model_report_query');
    return ['queries' => $model->get_queries()];
  }

	public static function private_show_default_page(model_request $request){
		return null;
	}
}