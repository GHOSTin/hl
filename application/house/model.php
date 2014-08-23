<?php
class model_house{

	public function get_house($id){
		$house = di::get('mapper_house')->find($id);
		if(!($house instanceof data_house))
			throw new RuntimeException('Дом не существует.');
		return $house;
	}

  public function edit_department($house_id, $department_id){
    $house = $this->get_house($house_id);
    $department = (new model_department(di::get('company')))
      ->get_department($department_id);
    $house->set_department($department);
    di::get('mapper_house')->update($house);
    return $house;
  }
}