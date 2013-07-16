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
}