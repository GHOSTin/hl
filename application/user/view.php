<?php
class view_user{
	
	public static function private_show_default_page($args){
		return load_template('user.private_show_default_page', $args);	
	}
}