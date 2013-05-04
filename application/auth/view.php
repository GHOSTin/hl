<?php
class view_auth{

	public static function public_login($args){
		if($_SESSION['user'] instanceof data_user){
			header('Location:/');
			exit();
		}else
			return load_template('auth.get_login_page', $args);
	}
    public static function public_show_auth_form($args){
        return load_template('auth.show_auth_form', $args);
    }
}