<?php
class view_query{


	public static function private_show_default_page($args){
		return load_template('query.private_show_default_page', $args);
	}

	public static function private_get_query_content($args){
		return load_template('query.private_get_query_content', ['query' => $args]);
	}	

	public static function private_get_query_title($args){
		return load_template('query.private_get_query_title', ['query' => $args]);
	}		
}