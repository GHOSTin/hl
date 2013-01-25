<?php
final class company_object extends data_object{

	public $id;
	public $name;
	public $status;
	public $sms_login;
	public $sms_password;
	public $sms_sender;
}



final class ctp_object extends data_object{

	public $id;
	public $ctpID;
	public $ctpCityID;
	public $city_id;
	public $ctp_name;
	public $name;
}

final class department_object extends data_object{

	public $departmentID;
	public $id;
	public $departmentStatus;
	public $status;
	public $departmentName;
	public $name;
}

final class flat_object extends data_object{

	public $id;
	public $flatID;
	public $flatHouseID;
	public $house_id;
	public $flatStatus;
	public $status;
	public $flatNumber;
	public $number;
}

final class group_object extends data_object{

	public $groupID;
	public $id;
	public $groupName;
	public $name;
	public $groupStatus;
	public $status;
	public $groupCompanyID;
	public $company_id;
}

final class house_object extends data_object{

	public $houseID;
	public $id;
	public $houseCityID;
	public $city_id;
	public $houseStreetID;
	public $street_id;
	public $houseDepartmentID;
	public $department_id;
	public $houseStatus;
	public $status;
	public $houseNumber;
	public $number;
	public $houseStreetName;
	public $street_name;
	public $houseDepartmentName;
	public $department_name;
}

final class number_object extends data_object{

	public $numberID;
	public $numberHouseID;
	public $numberNumber;
	public $numberType;
	public $numberStatus;
	public $numberFio;
	public $numberTelephone;
	public $numberCellphone;
	public $numberContactFio;
	public $numberContactTelephone;
	public $numberContactCellphone;
	# new
	public $id;
	public $house_id;
	public $number;
	public $type;
	public $status;
	public $fio;
	public $telephone;
	public $cellphone;
	public $contact_fio;
	public $contact_telephone;
	public $contact_cellphone;
	#
	public $numberHouseNumber;
	public $numberStreetName;
	public $numberFlatNumber;
	public $house_number;
	public $street_name;
	public $flat_number;
}

final class street_object extends data_object{

	public $streetID;
	public $id;
	public $streetCityID;
	public $city_id;
	public $streetName;
	public $name;
	public $streetStatus;
	public $status;
}