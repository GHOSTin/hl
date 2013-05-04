<?php
class view_auth{

    public static function public_show_auth_form($args){
        return load_template('auth.show_auth_form', $args);
    }
}