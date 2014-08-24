<?php
class controller_report{

	static $name = 'Отчеты';

    public static function private_clear_filter_query(model_request $request){
        (new model_report('query'))->clear_filter_query();
    }

    public static function private_get_query_reports(model_request $request){
        $model = new model_report('query');
        $company = di::get('company');
        $params = $model->get_params();

        if($params['street'] > 0){
            $street = di::get('em')->find('data_street', $params['street']);
            $houses = $street->get_houses();
        }
        return [
            'streets' => di::get('em')->getRepository('data_street')->findAll(),
            'users' => di::get('em')->getRepository('data_user')->findAll(),
            'departments' => (new model_department($company))->get_departments(),
            'query_work_types' => (new model_query_work_type($company))->get_query_work_types(),
            'houses' => $houses,
            'filters' => $params];
    }

    public static function private_report_query_one(model_request $request){
        $company = di::get('company');
        $params = (new model_report('query'))->get_params();
        $queries = di::get('mapper_query')->get_queries($params);
        $collection = new collection_query($company, $queries);
        $collection->init_numbers();
        $collection->init_users();
        $collection->init_works();
        return ['queries' => $collection];
    }

    public static function private_report_query_one_xls(model_request $request){
        header('Content-Disposition: attachment; filename=export.xml');
        header('Content-type: application/octet-stream');
        $company = di::get('company');
        $params = (new model_report('query'))->get_params();
        $queries = di::get('mapper_query')->get_queries($params);
        $collection = new collection_query($company, $queries);
        $collection->init_numbers();
        $collection->init_users();
        $collection->init_works();
        return ['queries' => $collection];
    }

    public static function private_set_time_begin(model_request $request){
        $time = explode('.', $_GET['time']);
        (new model_report('query'))
            ->set_time_begin(mktime(0, 0, 0, $time[1], $time[0], $time[2]));
    }

    public static function private_set_time_end(model_request $request){
        $time = explode('.', $_GET['time']);
        (new model_report('query'))
            ->set_time_end(mktime(23, 59, 59, $time[1], $time[0], $time[2]));
    }

    public static function private_set_filter_query_department(model_request $request){
        (new model_report('query'))
            ->set_filter_query_department($request->GET('id'));
    }

    public static function private_set_filter_query_status(model_request $request){
        (new model_report('query'))
            ->set_filter_query_status($request->GET('status'));
    }

    public static function private_set_filter_query_street(model_request $request){
        $street = (new model_report('query'))
            ->set_filter_query_street($request->GET('id'));
        if(!is_null($street)){
            return ['street' => $street];
        }else
            return true;
    }

    public static function private_set_filter_query_house(model_request $request){
        (new model_report('query'))
            ->set_filter_query_house($request->GET('id'));
    }

    public static function private_set_filter_query_worktype(model_request $request){
        (new model_report('query'))
            ->set_filter_query_worktype($request->GET('id'));
    }

	public static function private_show_default_page(model_request $request){
		return true;
	}
}