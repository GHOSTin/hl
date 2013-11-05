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
	private $id;

	/*
	* Статус заявки: 
	* open - открытая заявка,
	* working - заявка передана в работу,
	* close - закрытая заявка,
	* reopen - переоткрытая заявка.
	*/
	private $status;

	/*
	* Тип ициниатора:
	* number - лицевой счет,
	* house - дом.
	*/
	private $initiator;

	/*
	* Статус оплаты:
	* paid - оплачиваемая,
	* unpaid - неоплаичваемая,
	* recalculation - перерасчет.
	*/
	private $payment_status;

	/*
	* Статус реакции:
	* hight - аварийная заявка,
	* normal - заявка на участок,
	* planned - плановая заявка.
	*/
	private $warning_status;

	private $department;

	/*
	* Идентификатор дома.
	*/
	private $house;

	/*
	* Идентификатор причины закрытия.
	*/
	private $close_reason_id;

	/*
	* Идентификатор типа работ.
	*/
	private $work_type;

	/*
	* Время открытия.
	*/
	private $time_open;

	/*
	* Время когда заявка была передана в работу.
	*/
	private $time_work;

	/*
	* Время закрытия.
	*/
	private $time_close;

	/*
	* ФИО контактного лица.
	*/
	private $contact_fio;

	/*
	* Телефон контактного лица.
	*/
	private $contact_telephone;

	/*
	* Сотовый телефон контактного лица.
	*/
	private $contact_cellphone;

	/*
	* Описание заявки.
	*/
	private $description;

	/*
	* Описания причины закрытия.
	*/
	private $close_reason;

	/*
	* Номер заявки.
	*/
	private $number;

	/*
	* Данные инспекции.
	*/
	private $inspection;

	private $street;
	private $numbers = [];
	private $works = [];
	private $users = ['creator' => null, 'manager' => [],
										'observer' => [], 'performer' => []];

	public function add_number(data_number $number){
		if(array_key_exists($number->get_id(), $this->numbers))
			throw new e_model('Лицевой счет уже добавлен в заявку.');
		$this->numbers[$number->get_id()] = $number;
	}

	public function add_creator(data_user $user){
		$this->users['creator'] = $user;
	}

	public function add_manager(data_user $user){
		if(array_key_exists($user->get_id(), $this->users['manager']))
			throw new e_model("Менеджер уже добавлен в заявку.");
		$this->users['manager'][$user->get_id()] = $user;
	}

	public function add_observer(data_user $user){
		if(array_key_exists($user->get_id(), $this->users['observer']))
			throw new e_model("Исполнитель уже добавлен в заявку.");
		$this->users['observer'][$user->get_id()] = $user;
	}

	public function add_performer(data_user $user){
		if(array_key_exists($user->get_id(), $this->users['performer']))
			throw new e_model("Исполнитель уже добавлен в заявку.");
		$this->users['performer'][$user->get_id()] = $user;
	}

	public function remove_performer(data_user $user){
		if(!array_key_exists($user->get_id(), $this->users['performer']))
			throw new e_model("Исполнителя нет в заявке.");
		unset($this->users['performer'][$user->get_id()]);
	}

	public function remove_manager(data_user $user){
		if(!array_key_exists($user->get_id(), $this->users['manager']))
			throw new e_model("Исполнителя нет в заявке.");
		unset($this->users['manager'][$user->get_id()]);
	}

	public function add_work(data_query2work $work){
		if(array_key_exists($work->get_id(), $this->works))
			throw new e_model("Работа уже добавлен в заявку.");
		$this->works[$work->get_id()] = $work;
	}

	public function remove_work(data_query2work $work){
		if(!array_key_exists($work->get_id(), $this->works))
			throw new e_model("Работа не была в заявке.");
		unset($this->works[$work->get_id()]);
	}

	public function add_work_type(data_query_work_type $wt){
		$this->work_type = $wt;
	}

	public function get_work_type(){
		return $this->work_type;
	}

	public function get_numbers(){
		return $this->numbers;
	}

	public function get_close_reason(){
		return $this->close_reason;
	}

	public function get_creator(){
		return $this->users['creator'];
	}

	public function get_managers(){
		return $this->users['manager'];
	}

	public function get_observers(){
		return $this->users['observer'];
	}

	public function get_performers(){
		return $this->users['performer'];
	}

	public function get_works(){
		return $this->works;
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

	public function get_initiator(){
		return $this->initiator;
	}

	public function get_department(){
		return $this->department;
	}

	public function get_house(){
		return $this->house;
	}

	public function get_street(){
		return $this->street;
	}

	public function get_number(){
		return $this->number;
	}

	public function get_time_open(){
		return $this->time_open;
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

	public function set_id($id){
		$id = (int) $id;
		if($id > 4294967295 OR $id < 1)
		    throw new e_model('Идентификатор заявки задан не верно.');
		$this->id = $id;
	}

	public function set_number($number){
		if(!preg_match('/^[0-9]{1,6}$/', $number))
		    throw new e_model('Номер заявки задан не верно.');
		$this->number = (int) $number;
	}

	public function set_time_open($time){
		$this->time_open = (int) $time;
	}

	public function set_close_reason($reason){
		$this->close_reason = $reason;
	}

	public function set_department(data_department $department){
		$this->department = $department;
	}

	public function set_initiator($initiator){
		if(!in_array($initiator, ['house', 'number'], true))
			throw new e_model('Неверный тип заявки.');
		$this->initiator = (string) $initiator;
	}

	public function set_house(data_house $house){
		$this->house = $house;
	}

	public function set_street(data_street $street){
		$this->street = $street;
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
		if(!in_array($status, ['paid', 'unpaid', 'recalculation'], true))
		    throw new e_model('Статус оплаты заявки задан не верно.');
		$this->payment_status = $status;
	}

	public function set_status($status){
		if(!in_array($status, ['open', 'close', 'working', 'reopen'], true))
		    throw new e_model('Статус заявки задан не верно.');
		$this->status = $status;
	}

	public function set_time_close($time){
		if(!in_array($this->status, ['open', 'working'], true)){
			if( $time < $this->time_open)
				throw new e_model('Время закрытия заявки не может быть меньше времени открытия.');
			if($time < $this->time_work)
				throw new e_model('Время закрытия заявки не может быть меньше времени передачи в работу.');
		}
		$this->time_close = (int) $time;
	}

	public function set_time_work($time){
		if($time < $this->time_open)
			throw new e_model('Время закрытия заявки не может быть меньше времени открытия.');
		$this->time_work = (int) $time;
	}

	public function set_warning_status($status){
		if(!in_array($status, ['hight', 'normal', 'planned'], true))
		    throw new e_model('Статус ворнинга заявки задан не верно.');
		$this->warning_status = $status;
	}

	public function verify(){
    if(func_num_args() < 0)
        throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
        verify_query::$value($this);
  }
}