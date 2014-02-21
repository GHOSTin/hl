<?php
class model_company{

	public static function get_companies(){
		return di::get('mapper_company')->find_all();
	}
}