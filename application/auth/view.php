<?php
class view_auth{

	public static function get_login_page(){
		model_template::load_template('auth.get_login_page');
	}
}