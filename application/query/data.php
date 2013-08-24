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

	private $numbers;

	public function add_number(data_number $number){
		$this->numbers[$number->id] = $number;
	}

	public function get_numbers(){
		return $this->numbers;
	}

	public function get_close_reason(){
		return $this->close_reason;
	}

	public function get_company_id(){
		return $this->company_id;
	}

	public function get_contact_cellphone(){
		return $this->contact_cellphone;
	}

	public function get_contact_fio(){
		return $this->contact_fio;
	}

	public function get_contact_telephone(){
		return $this->contact_telephone;
	}

	public function get_description(){
		return $this->description;
	}

	public function get_id(){
		return $this->id;
	}

	public function get_payment_status(){
		return $this->payment_status;
	}

	public function get_status(){
		return $this->status;
	}

	public function get_time_close(){
		return $this->time_close;
	}

	public function get_time_work(){
		return $this->time_work;
	}

	public function get_warning_status(){
		return $this->warning_status;
	}

	public function get_work_type_id(){
		return $this->worktype_id;
	}

	public function get_work_type_name(){
		return $this->work_type_name;
	}

	public function set_close_reason($reason){
		$this->close_reason = $reason;
	}

	public function set_contact_cellphone($cellphone){
		$this->contact_cellphone = $cellphone;
	}

	public function set_contact_fio($fio){
		$this->contact_fio = $fio;
	}

	public function set_contact_telephone($telephone){
		$this->contact_telephone = $telephone;
	}

	public function set_description($description){
		$this->description = $description;
	}

	public function set_payment_status($status){
		$this->payment_status = $status;
	}

	public function set_status($status){
		$this->status = $status;
	}

	public function set_time_close($time){
		$this->time_close = $time;
	}

	public function set_time_work($time){
		$this->time_work = $time;
	}

	public function set_warning_status($status){
		$this->warning_status = $status;
	}

	public function set_work_type_id($id){
		$this->worktype_id = $id;
	}

	public function set_work_type_name($name){
		$this->work_type_name = $name;
	}

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_query::$value($this);
    }
}