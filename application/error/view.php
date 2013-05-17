<?php
class view_error{

    public static function show_error($e){
        $args['error'] = $e;
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            return load_template('error.show_ajax_error', $args);
        else
            return load_template('error.show_html_error', $args);
    }

    public static function show_404(){
        header("HTTP/1.0 404 Not Found");
        return load_template('error.show_404_error', []);
    }
}