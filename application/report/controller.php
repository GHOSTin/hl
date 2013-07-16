<?php
class controller_report{

	static $name = 'Отчеты';

    public static function private_get_query_reports(){
        $company = model_session::get_company();
        $session = model_session::get_session();
        if($session->get('report') !== 'query'){
            $session->set('report', 'query');
            $session->set('filters', []);
        }
        return [
            'streets' => model_street::get_streets(new data_street()),
            'users' => model_user::get_users(new data_user()),
            'departments' => model_department::get_departments($company, new data_department()),
            'query_work_types' => model_query_work_type::get_query_work_types($company, new data_query_work_type()),
            'houses' => $houses];
    }

    public static function private_set_time_begin(){
        $session = model_session::get_session();
        $time = explode('.', $_GET['time']);
        $session = model_session::get_session();
        $filters = $session->get('filters');
        if(!is_array($filters))
            $filters = [];
        $filters['time_begin'] = mktime(0, 0, 0, $time[1], $time[0], $time[2]);
        $session->set('filters', $filters);
    }

	public static function private_show_default_page(){
		return true;
	}
}