<?php
class controller_query{

	public static function private_show_default_page(){
		$queries = model_query::get_queries($args);
		if($queries !== false){
			$args['queries'] = $queries;
			return view_query::private_show_default_page($args);
		}
	}
}