<?php
class controller_report{

	static $name = 'Отчеты';

	public static function private_get_query_reports(model_request $request){
    $em = di::get('em');
    $work_types = $em->getRepository('data_query_work_type')
      ->findBy([], ['name' => 'ASC']);
    $departments = $em->getRepository('data_department')
      ->findBy([], ['name' => 'ASC']);
		$model = di::get('model_report_query');
		return ['filters' => $model->get_filters(),
      'query_work_types' => $work_types, 'departments' => $departments];
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

  public static function private_set_filter_query_worktype(model_request $request){
    $model = di::get('model_report_query');
    $model->set_worktype($request->GET('id'));
    return null;
  }

  public static function private_set_filter_query_department(model_request $request){
    $model = di::get('model_report_query');
    $model->set_department($request->GET('id'));
    return null;
  }

  public static function private_set_filter_query_status(model_request $request){
    $model = di::get('model_report_query');
    $model->set_status($request->GET('status'));
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