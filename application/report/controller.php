<?php
class controller_report{

	static $name = 'Отчеты';

    public static function private_get_query_reports(){
        $company = model_session::get_company();
        $session = model_session::get_session();
        if($session->get('report') !== 'query'){
            $time = getdate();
            $session->set('report', 'query');
            $session->set('filters', 
                        ['time_begin' => mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']),
                        'time_end' => mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year'])]);
        }
        $filters = $session->get('filters');
        if($filters['street_id'] > 0){
            $street = new data_street();
            $street->id = $filters['street_id'];
            model_street::verify_id($street);
            $houses = model_street::get_houses($street);
        }
        return [
            'streets' => model_street::get_streets(new data_street()),
            'users' => model_user::get_users(new data_user()),
            'departments' => model_department::get_departments($company, new data_department()),
            'query_work_types' => model_query_work_type::get_query_work_types($company, new data_query_work_type()),
            'houses' => $houses,
            'filters' => $filters];
    }

    public static function private_report_query_one(){
        $query = model_report::build_query_param(model_session::get_session()->get('filters'));
        $company = model_session::get_company();
        return ['queries' => model_query::get_queries($company, $query),
                'users' => model_query::get_users($company, $query),
                'works' => model_query::get_works($company, $query),
                'numbers' => model_query::get_numbers($company, $query)];
    }

    public static function private_set_time_begin(){
        $time = explode('.', $_GET['time']);
        model_report::set_filter('time_begin', mktime(0, 0, 0, $time[1], $time[0], $time[2]));
    }

    public static function private_set_time_end(){
        $time = explode('.', $_GET['time']);
        model_report::set_filter('time_end', mktime(23, 59, 59, $time[1], $time[0], $time[2]));
    }

    public static function private_set_filter_query_department(){
        model_report::set_filter_query_department($_GET['id']);
    }

    public static function private_set_filter_query_status(){
        model_report::set_filter_query_status($_GET['status']);
    }

    public static function private_set_filter_query_street(){
        model_report::set_filter_query_street($_GET['id']);
        if($_GET['id'] !== 'all'){
            $street = new data_street();
            $street->id = $_GET['id'];
            model_street::verify_id($street);
            return ['houses' => model_street::get_houses($street)];
        }else
            return true;
    }

    public static function private_set_filter_query_house(){
        model_report::set_filter_query_house($_GET['id']);
    }

    public static function private_set_filter_query_worktype(){
        model_report::set_filter_query_worktype($_GET['id']);
    }

	public static function private_show_default_page(){
		return true;
	}
}