<?php
/*
* Связь с таблицей `queries`.
* Заявки ассоциированы с компанией.
* Каждый год номер заявки начинает идти с 1, а иджентификатор заявки увеличивается дальше.
*/
final class data_query extends data_object{
	/*
	* Идентификатор заявки уникален для компании.
	*/
	public $id;
	/*
	* Статус заявки: 
	* open - открытая заявка 
	* working - заявка передана в работу
	* close - закрытая заявка
	* reopen - переоткрытая заявка
	*/
	public $status;
	/*
	* Тип ициниатора.
	* number - лицевой счет
	* house - дом
	*/
	public $initiator;
	public $payment_status;
	public $warning_status;
	public $department_id;
	public $house_id;
	public $close_reason_id;
	public $worktype_id;
	public $work_type_name;
	public $time_open;
	public $time_work;
	public $time_close;
	public $contact_fio;
	public $contact_telephone;
	public $contact_cellphone;
	public $description;
	public $close_reason;
	public $number;
	public $inspection;
	public $house_number;
	public $street_name;
	public $company_id;
}