<?php
/*
* Связь с таблицей `flats`.
* Квартиры глобальны, но пока привязаны к компании.
*/
final class data_flat extends data_object{

	public $company_id;
	public $house_id;
    public $id;
    public $number;
	public $status;
}