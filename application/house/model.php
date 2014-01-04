<?php
class model_house{

	public function get_house($id){
		$house = (new mapper_house())->find($id);
		if(!($house instanceof data_house))
			throw new e_model('Дом не существует.');
		return $house;
	}
}