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
	* open - открытая заявка,
	* working - заявка передана в работу,
	* close - закрытая заявка,
	* reopen - переоткрытая заявка.
	*/
	public $status;
	/*
	* Тип ициниатора:
	* number - лицевой счет,
	* house - дом.
	*/
	public $initiator;
	/*
	* Статус оплаты:
	* paid - оплачиваемая,
	* unpaid - неоплаичваемая,
	* recalculation - перерасчет.
	*/
	public $payment_status;
	/*
	* Статус реакции:
	* hight - аварийная заявка,
	* normal - заявка на участок,
	* planned - плановая заявка.
	*/
	public $warning_status;
	/*
	* Идентификатор участка.
	*/
	public $department_id;
	/*
	* Название участка
	*/
	public $department_name;
	/*
	* Идентификатор дома.
	*/
	public $house_id;
	/*
	* Идентификатор причины закрытия.
	*/
	public $close_reason_id;
	/*
	* Идентификатор типа работ.
	*/
	public $worktype_id;
	/*
	* Имя типа работ.
	*/
	public $work_type_name;
	/*
	* Время открытия.
	*/
	public $time_open;
	/*
	* Время когда заявка была передана в работу.
	*/
	public $time_work;
	/*
	* Время закрытия.
	*/
	public $time_close;
	/*
	* ФИО контактного лица.
	*/
	public $contact_fio;
	/*
	* Телефон контактного лица.
	*/
	public $contact_telephone;
	/*
	* Сотовый телефон контактного лица.
	*/
	public $contact_cellphone;
	/*
	* Описание заявки.
	*/
	public $description;
	/*
	* Описания причины закрытия.
	*/
	public $close_reason;
	/*
	* Номер заявки.
	*/
	public $number;
	/*
	* Данные инспекции.
	*/
	public $inspection;
	/*
	* Номер дома.
	*/
	public $house_number;
	/*
	* Имя улицы.
	*/
	public $street_name;
	/*
	* Идентификатор компании.
	*/
	public $company_id;
	/*
	* Идентификатор улицы
	*/
	public $street_id;
}