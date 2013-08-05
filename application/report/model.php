<?php
class model_report{

    public static function clear_filter_query(){
        $time = getdate();
        $filters = ['time_begin' => mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']),
                    'time_end' => mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year'])];
        model_session::get_session()->set('filters', $filters);
        return ['filters' => $filters];
    }

    public static function set_filter($key, $value){
        $session = model_session::get_session();
        $filters = $session->get('filters');
        if(!is_array($filters))
            $filters = [];
        $filters[$key] = $value;
        $session->set('filters', $filters);
    }

    public static function build_query_param($filters){
        $query = new data_query();
        $query->time_open['begin'] = $filters['time_begin'];
        $query->time_open['end'] = $filters['time_end'];

        if(!empty($filters['status']))
            if($filters['status'] === 'all')
                $query->status = null;
            else
                $query->status = $filters['status'];
        if(!empty($filters['department_id']) AND $filters['department_id'] !== 'all')
            $query->department_id = $filters['department_id'];
        else
            $query->department_id = null;
        if(!empty($filters['worktype_id']) AND $filters['worktype_id'] !== 'all')
            $query->worktype_id = $filters['worktype_id'];
        else
            $query->worktype_id = null;
        if(!empty($filters['street_id']) AND $filters['street_id'] !== 'all')
            $query->street_id = $filters['street_id'];
        else
            $query->street_id = null;
        if(!empty($filters['house_id']) AND $filters['house_id'] !== 'all')
            $query->house_id = $filters['house_id'];
        else
            $query->house_id = null;
        return $query;
    }

    public static function set_filter_query_department($department_id){
        $session = model_session::get_session();
        $filters = $session->get('filters');
        if($department_id === 'all')
            unset($filters['department_id']);
        else{
            $query = new data_query();
            $query->department_id = $department_id;
            model_query::verify_department_id($query);
            $filters['department_id'] = $department_id;
        }
        $session->set('filters', $filters);
    }

    public static function set_filter_query_status($status){
        $query = new data_query();
        $query->status = $status;
        model_query::verify_status($query);
        $session = model_session::get_session();
        $filters = $session->get('filters');
        $filters['status'] = $status;
        $session->set('filters', $filters);
    }

    public static function set_filter_query_street($street_id){
        $session = model_session::get_session();
        $filters = $session->get('filters');
        if($street_id === 'all'){
            unset($filters['street_id']);
            unset($filters['house_id']);
        }else{
            $query = new data_query();
            $query->street_id = $street_id;
            model_query::verify_street_id($query);
            $filters['street_id'] = $street_id;
            unset($filters['house_id']);
        }
        $session->set('filters', $filters);
    }

    public static function set_filter_query_house($house_id){
        $session = model_session::get_session();
        $filters = $session->get('filters');
        if($house_id === 'all'){
            unset($filters['house_id']);
        }else{
            $query = new data_query();
            $query->house_id = $house_id;
            model_query::verify_house_id($query);
            $filters['house_id'] = $house_id;
        }
        $session->set('filters', $filters);
    }

    public static function set_filter_query_worktype($worktype_id){
        $session = model_session::get_session();
        $filters = $session->get('filters');
        if($worktype_id === 'all')
            unset($filters['worktype_id']);
        else{
            $query = new data_query();
            $query->worktype_id = $worktype_id;
            model_query::verify_work_type_id($query);
            $filters['worktype_id'] = $worktype_id;
        }
        $session->set('filters', $filters);
    }
}