<?php
class model_street{

	public static function get_street($id){
		$street = (new mapper_street)->find($id);
		if(!($street instanceof data_street))
			throw new e_model('Нет улицы');
		return $street;
	}

	public static function get_street_by_name($name){
		$street = (new mapper_street)->get_street_by_name($name);
		if(!($street instanceof data_street))
			throw new e_model('Нет улицы');
		return $street;
	}

	public static function get_streets(){
		return (new mapper_street)->get_streets();
	}
}