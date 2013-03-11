<?php
class view_profile{
	public static function private_show_default_page($args){
		// var_dump($args); exit();
		return load_template('profile.private_show_default_page', $args);
	}
	public static function private_get_notification_center_content($args){
		return load_template('profile.private_get_notification_center_content', ['ans' => $args]);
	}
}