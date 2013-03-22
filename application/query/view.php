<?php
class view_query{
	public static function private_get_documents($args){
		return load_template('query.private_get_documents', $args);
	}		
	public static function private_get_day($args){
		return load_template('query.private_get_day', $args);
	}
	public static function private_create_query($args){
		return load_template('query.private_get_day', $args);
	}	
	public static function private_get_dialog_create_query(){
		return load_template('query.private_get_dialog_create_query', []);
	}		
	public static function private_get_dialog_initiator($args){
		return load_template('query.private_get_dialog_initiator', $args);
	}	
	public static function private_get_initiator($args){
		return load_template('query.private_get_initiator', $args);
	}		
	public static function private_clear_filters($args){
		return load_template('query.private_get_day', $args);
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
		return load_template('query.private_get_day', $args);
	}
	public static function private_set_status($args){
		return load_template('query.private_get_day', $args);
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
}