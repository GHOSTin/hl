<?php
class model_company{

	public static function get_companies(){
		return (new mapper_company(di::get('pdo')))->find_all();
	}
}