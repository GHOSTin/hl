<?php
class view_query{

	public static function private_add_user($args){
		return load_template('query.private_add_user', $args);
	}

	public static function private_add_work($args){
		return load_template('query.private_add_work', $args);
	}	

	public static function private_get_documents($args){
		return load_template('query.private_get_documents', $args);
	}

	public static function private_get_day($args){
		return load_template('query.query_titles', $args);
	}

	public static function private_close_query($args){
		return load_template('query.private_get_query_content', $args);
	}

	public static function private_to_working_query($args){
		return load_template('query.private_get_query_content', $args);
	}

	public static function private_create_query($args){
		return load_template('query.query_titles', $args);
	}

	public static function private_get_dialog_close_query($args){
		return load_template('query.private_get_dialog_close_query', $args);
	}

	public static function private_get_dialog_to_working_query($args){
		return load_template('query.private_get_dialog_to_working_query', $args);
	}

	public static function private_get_dialog_add_user($args){
		return load_template('query.private_get_dialog_add_user', $args);
	}

	public static function private_get_dialog_add_work($args){
		return load_template('query.private_get_dialog_add_work', $args);
	}

	public static function private_get_dialog_create_query(){
		return load_template('query.private_get_dialog_create_query', []);
	}

	public static function private_get_dialog_edit_description($args){
		return load_template('query.private_get_dialog_edit_description', $args);
	}

	public static function private_get_dialog_edit_contact_information($args){
		return load_template('query.private_get_dialog_edit_contact_information', $args);
	}

	public static function private_get_dialog_edit_payment_status($args){
		return load_template('query.private_get_dialog_edit_payment_status', $args);
	}

	public static function private_get_dialog_edit_warning_status($args){
		return load_template('query.private_get_dialog_edit_warning_status', $args);
	}

	public static function private_get_dialog_edit_work_type($args){
		return load_template('query.private_get_dialog_edit_work_type', $args);
	}

	public static function private_get_dialog_initiator($args){
		return load_template('query.private_get_dialog_initiator', $args);
	}

	public static function private_get_dialog_remove_user($args){
		return load_template('query.private_get_dialog_remove_user', $args);
	}

	public static function private_get_dialog_remove_work($args){
		return load_template('query.private_get_dialog_remove_work', $args);
	}

	public static function private_get_initiator($args){
		return load_template('query.private_get_initiator', $args);
	}

	public static function private_clear_filters($args){
		return load_template('query.query_titles', $args);
	}

	public static function private_get_houses($args){
		$args['houses'] = $args;
		return load_template('query.private_get_houses', $args);
	}

	public static function private_get_numbers($args){
		$args['numbers'] = $args;
		return load_template('query.private_get_numbers', $args);
	}

	public static function private_print_query($args){
		return load_template('query.private_print_query', $args);
	}

	public static function private_get_search_result($args){
		return load_template('query.query_titles', $args);
	}

	public static function private_set_status($args){
		return load_template('query.query_titles', $args);
	}

	public static function private_show_default_page($args){
		return load_template('query.private_show_default_page', $args);
	}

	public static function private_get_search(){
		return load_template('query.private_get_search', []);
	}

	public static function private_get_timeline($args){
		return load_template('query.private_get_timeline', $args);
	}

	public static function private_get_query_content($args){
		return load_template('query.private_get_query_content', $args);
	}

	public static function private_get_query_title($args){
		return load_template('query.private_get_query_title', $args);
	}

	public static function private_get_query_numbers($args){
		return load_template('query.private_get_query_numbers', $args);
	}

	public static function private_get_query_users($args){
		return load_template('query.private_get_query_users', $args);
	}

	public static function private_get_query_works($args){
		return load_template('query.private_get_query_works', $args);
	}

	public static function private_get_user_options($args){
		return load_template('query.private_get_user_options', $args);
	}

	public static function private_get_work_options($args){
		return load_template('query.private_get_work_options', $args);
	}

	public static function private_update_description($args){
		return load_template('query.private_update_description', $args);
	}

	public static function private_update_contact_information($args){
		return load_template('query.private_update_contact_information', $args);
	}

	public static function private_update_payment_status($args){
		return load_template('query.private_update_payment_status', $args);
	}

	public static function private_update_warning_status($args){
		return load_template('query.private_update_warning_status', $args);
	}

	public static function private_remove_user($args){
		return;
	}

	public static function private_remove_work($args){
		return;
	}
	
	public static function private_update_work_type($args){
		return load_template('query.private_update_work_type', $args);
	}			
}