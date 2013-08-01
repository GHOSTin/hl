<?php
class view_user{

    public static function private_add_profile($args){
        return load_template('user.private_get_user_profiles', $args);
    }

    public static function private_create_group($args){
        return load_template('user.private_get_group_letter', $args);
    }

    public static function private_create_user($args){
        return load_template('user.private_get_user_letter', $args);
    }

    public static function private_delete_profile($args){
        return load_template('user.private_delete_profile', $args);
    }

    public static function private_add_user($args){
        return load_template('user.private_add_user', $args);
    }

    public static function private_exclude_user($args){
        return load_template('user.private_add_user', $args);
    }

    public static function private_get_dialog_create_group($args){
        return load_template('user.private_get_dialog_create_group', $args);
    }

    public static function private_get_dialog_create_user($args){
        return load_template('user.private_get_dialog_create_user', $args);
    }

    public static function private_get_dialog_add_profile($args){
        return load_template('user.private_get_dialog_add_profile', $args);
    }

    public static function private_get_dialog_add_user($args){
        return load_template('user.private_get_dialog_add_user', $args);
    }

    public static function private_get_dialog_delete_profile($args){
        return load_template('user.private_get_dialog_delete_profile', $args);
    }

    public static function private_get_dialog_edit_fio($args){
        return load_template('user.private_get_dialog_edit_fio', $args);
    }

    public static function private_get_dialog_edit_group_name($args){
        return load_template('user.private_get_dialog_edit_group_name', $args);
    }

    public static function private_get_dialog_edit_password($args){
        return load_template('user.private_get_dialog_edit_password', $args);
    }

    public static function private_get_dialog_edit_login($args){
        return load_template('user.private_get_dialog_edit_login', $args);
    }

    public static function private_get_dialog_edit_user_status($args){
        return load_template('user.private_get_dialog_edit_user_status', $args);
    }

    public static function private_get_dialog_exclude_user($args){
        return load_template('user.private_get_dialog_exclude_user', $args);
    }

    public static function private_get_company_content($args){
        return load_template('user.private_get_company_content', $args);
    }

    public static function private_get_profile_content($args){
        return load_template('user.private_get_profile_content', $args);
    }

    public static function private_get_restriction_content($args){
        return load_template('user.private_get_restriction_content', $args);
    }

    public static function private_get_group_letters($args){
        return load_template('user.private_get_group_letters', $args);
    }

    public static function private_get_group_content($args){
        return load_template('user.private_get_group_content', $args);
    }

    public static function private_get_group_profile($args){
        return load_template('user.private_get_group_profile', $args);
    }

    public static function private_get_group_users($args){
        return load_template('user.private_get_group_users', $args);
    }

    public static function private_get_user_content($args){
        return load_template('user.private_get_user_content', $args);
    }

    public static function private_get_user_information($args){
        return load_template('user.private_get_user_information', $args);
    }

    public static function private_get_user_profiles($args){
        return load_template('user.private_get_user_profiles', $args);
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

    public static function private_update_group_name($args){
        return load_template('user.private_update_group_name', $args);
    }

    public static function private_update_fio($args){
        return load_template('user.private_update_fio', $args);
    }

    public static function private_update_password($args){
        return load_template('user.private_update_password', $args);
    }

    public static function private_update_rule($args){
        return load_template('user.private_update_rule', $args);
    }

    public static function private_update_login($args){
        return load_template('user.private_update_login', $args);
    }

    public static function private_update_user_status($args){
        return load_template('user.private_get_user_information', $args);
    }
}