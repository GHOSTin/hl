<?php
class view_user{

    public static function private_get_user_letter($args){
        return load_template('user.private_get_user_letter', $args);
    }

	public static function private_show_default_page($args){
		return load_template('user.private_show_default_page', $args);
	}
}