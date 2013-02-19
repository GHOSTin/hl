<?php
class view_auth{
	public static function public_login(){
		return load_template('auth.get_login_page');
	}
}