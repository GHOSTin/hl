<?php

class model_street{

	public function get_street($id){
		$street = di::get('mapper_street')->find($id);
		if(!($street instanceof data_street))
			throw new e_model('Нет улицы');
		return $street;
	}

	public function get_streets(){
		return di::get('mapper_street')->get_streets();
	}
}