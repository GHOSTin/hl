<?php
class model_report{

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
        return $query;
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
}