<?php
class controller_report{

	static $name = 'Отчеты';

    public static function private_clear_filter_query(model_request $request){
        model_report::clear_filter_query();
    }

    public static function private_get_query_reports(model_request $request){
        $model = new model_report('query');
        $company = model_session::get_company();
        // $filters = $session->get('filters');
        // if($filters['street_id'] > 0){
        //     $street = new data_street();
        //     $street->id = $filters['street_id'];
        //     $street->verify('id');
        //     $houses = model_street::get_houses($street);
        // }
        return [
            'streets' => (new model_street)->get_streets(),
            'users' => (new model_user)->get_users(),
            'departments' => (new model_department($company))->get_departments(),
            'query_work_types' => (new model_query_work_type($company))->get_query_work_types(),
            'houses' => $houses,
            'filters' => $filters];
    }

    public static function private_report_query_one(model_request $request){
        $company = model_session::get_company();
        $params = (new model_report('query'))->get_params();
        $queries = (new mapper_query($company))->get_queries($params);
        $collection = new collection_query($company, $queries);
        $collection->init_numbers();
        $collection->init_users();
        $collection->init_works();
        return ['queries' => $collection];
    }

    public static function private_report_query_one_xls(model_request $request){
        $query = model_report::build_query_param(model_session::get_session()->get('filters'));
        $company = model_session::get_company();
        header('Content-Disposition: attachment; filename=export.xml');
        header('Content-type: application/octet-stream');
        return ['queries' => model_query::get_queries($company, $query),
                'users' => model_query::get_users($company, $query),
                'works' => model_query::get_works($company, $query),
                'numbers' => model_query::get_numbers($company, $query)];
    }

    public static function private_set_time_begin(model_request $request){
        $time = explode('.', $_GET['time']);
        model_report::set_filter('time_begin', mktime(0, 0, 0, $time[1], $time[0], $time[2]));
    }

    public static function private_set_time_end(model_request $request){
        $time = explode('.', $_GET['time']);
        model_report::set_filter('time_end', mktime(23, 59, 59, $time[1], $time[0], $time[2]));
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
        model_report::set_filter_query_street($_GET['id']);
        if($_GET['id'] !== 'all'){
            $street = new data_street();
            $street->id = $_GET['id'];
            $street->verify('id');
            return ['houses' => model_street::get_houses($street)];
        }else
            return true;
    }

    public static function private_set_filter_query_house(model_request $request){
        model_report::set_filter_query_house($_GET['id']);
    }

    public static function private_set_filter_query_worktype(model_request $request){
        (new model_report('query'))
            ->set_filter_query_worktype($request->GET('id'));
    }

	public static function private_show_default_page(model_request $request){
		return true;
	}
}