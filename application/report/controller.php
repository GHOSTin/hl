<?php
class controller_report{

	static $name = 'Отчеты';

	public static function private_get_query_reports(model_request $request){
    $em = di::get('em');
    $work_types = $em->getRepository('data_query_work_type')
      ->findBy([], ['name' => 'ASC']);
    $departments = $em->getRepository('data_department')
      ->findBy([], ['name' => 'ASC']);
    $streets = $em->getRepository('data_street')
      ->findBy([], ['name' => 'ASC']);
		$model = di::get('model_report_query');
    $filters = $model->get_filters();
    $houses = [];
    if(!empty($filters['street']))
      $houses = $em->getRepository('data_house')->findByStreet($filters['street']);
		return ['filters' => $filters,
      'query_work_types' => $work_types, 'departments' => $departments,
      'streets' => $streets, 'houses' => $houses];
	}

  public static function private_set_time_begin(model_request $request){
    $model = di::get('model_report_query');
    $model->set_time_begin(strtotime($request->GET('time')));
  }

  public static function private_set_time_end(model_request $request){
    $model = di::get('model_report_query');
    $model->set_time_end(strtotime($request->GET('time')));
  }

  public static function private_set_filter_query_worktype(model_request $request){
    $model = di::get('model_report_query');
    $model->set_worktype($request->GET('id'));
  }

  public static function private_set_filter_query_department(model_request $request){
    $model = di::get('model_report_query');
    $model->set_department($request->GET('id'));
  }

  public static function private_set_filter_query_status(model_request $request){
    $model = di::get('model_report_query');
    $model->set_status($request->GET('status'));
  }

  public static function private_set_filter_query_street(model_request $request){
    $model = di::get('model_report_query');
    $model->set_street($request->GET('id'));
    return ['street' => di::get('em')->find('data_street', $request->GET('id'))];
  }

  public static function private_set_filter_query_house(model_request $request){
    $model = di::get('model_report_query');
    $model->set_house($request->GET('id'));
  }

  public static function private_report_query_one(model_request $request){
    $model = di::get('model_report_query');
    return ['queries' => $model->get_queries()];
  }

	public static function private_show_default_page(model_request $request){
	}
}