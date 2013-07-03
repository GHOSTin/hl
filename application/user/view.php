<?php
class view_user{

    public static function private_get_dialog_edit_fio($args){
        return load_template('user.private_get_dialog_edit_fio', $args);
    }

    public static function private_get_dialog_edit_password($args){
        return load_template('user.private_get_dialog_edit_password', $args);
    }

    public static function private_get_dialog_edit_login($args){
        return load_template('user.private_get_dialog_edit_login', $args);
    }

    public static function private_get_group_letters($args){
        return load_template('user.private_get_group_letters', $args);
    }

    public static function private_get_group_content($args){
        return load_template('user.private_get_group_content', $args);
    }

    public static function private_get_group_users($args){
        return load_template('user.private_get_group_users', $args);
    }

    public static function private_get_user_content($args){
        return load_template('user.private_get_user_content', $args);
    }

    public static function private_get_group_letter($args){
        return load_template('user.private_get_group_letter', $args);
    }

    public static function private_get_user_letter($args){
        return load_template('user.private_get_user_letter', $args);
    }

    public static function private_get_user_letters($args){
        return load_template('user.private_get_user_letters', $args);
    }

	public static function private_show_default_page($args){
		return load_template('user.private_show_default_page', $args);
	}

    public static function private_update_fio($args){
        return load_template('user.private_update_fio', $args);
    }

    public static function private_update_password($args){
        return load_template('user.private_update_password', $args);
    }

    public static function private_update_login($args){
        return load_template('user.private_update_login', $args);
    }
}