<?php
/**
* @Entity(repositoryClass="repository_query")
* @Table(name="queries")
*/
class data_query extends data_object{

	/**
  * @Id
  * @Column(name="id", type="integer")
  */
	private $id;

	/**
  * @Column(name="status", type="string")
  */
	private $status;

	/**
  * @Column(name="initiator", type="string")
  */
	private $initiator;

	/**
  * @Column(name="payment_status", type="string")
  */
	private $payment_status;

	/**
  * @Column(name="warning_type", type="string")
  */
	private $warning_status;

	/**
  * @ManyToOne(targetEntity="data_department")
  */
	private $department;

	/**
  * @ManyToOne(targetEntity="data_house")
  */
	private $house;
	private $close_reason_id;

	/**
  * @ManyToOne(targetEntity="data_query_work_type")
  * @JoinColumn(name="query_worktype_id", referencedColumnName="id")
  */
	private $work_type;

	/**
  * @Column(name="opentime", type="string")
  */
	private $time_open;

	/**
  * @Column(name="worktime", type="string")
  */
	private $time_work;

	/**
  * @Column(name="closetime", type="string")
  */
	private $time_close;

	/**
  * @Column(type="string")
  */
	private $contact_fio;

	/**
  * @Column(type="string")
  */
	private $contact_telephone;

	/**
  * @Column(type="string")
  */
	private $contact_cellphone;

	/**
  * @Column(name="description", type="string")
  */
	private $description;

	/**
  * @Column(name="reason", type="string")
  */
	private $close_reason;

	/**
  * @Column(name="querynumber", type="string")
  */
	private $number;
	private $inspection;
	private $street;
	private $numbers;
	private $works;
	private $users = ['creator' => null, 'manager' => [],
										'observer' => [], 'performer' => []];
	private $comments;

	public static $initiator_list = ['number', 'house'];
	public static $payment_status_list = ['paid', 'unpaid', 'recalculation'];
	public static $status_list = ['open', 'close', 'working', 'reopen'];
	public static $warning_status_list = ['hight', 'normal', 'planned'];

	public function add_number(data_number $number){
		if(array_key_exists($number->get_id(), $this->numbers))
			throw new DomainException('Лицевой счет уже добавлен в заявку.');
		$this->numbers[$number->get_id()] = $number;
	}

	public function add_comment(data_query2comment $comment){
		$id = $comment->get_user()->get_id().'_'.$comment->get_time();
		if(array_key_exists($id, $this->comments))
			throw new DomainException('Комментарий уже существует.');
		$this->comments[$id] = $comment;
	}

	public function add_creator(data_user $user){
		$this->users['creator'] = $user;
	}

	public function add_manager(data_user $user){
		if(array_key_exists($user->get_id(), $this->users['manager']))
			throw new DomainException("Менеджер уже добавлен в заявку.");
		$this->users['manager'][$user->get_id()] = $user;
	}

	public function add_observer(data_user $user){
		if(array_key_exists($user->get_id(), $this->users['observer']))
			throw new DomainException("Исполнитель уже добавлен в заявку.");
		$this->users['observer'][$user->get_id()] = $user;
	}

	public function add_performer(data_user $user){
		if(array_key_exists($user->get_id(), $this->users['performer']))
			throw new DomainException("Исполнитель уже добавлен в заявку.");
		$this->users['performer'][$user->get_id()] = $user;
	}

	public function remove_performer(data_user $user){
		if(!array_key_exists($user->get_id(), $this->users['performer']))
			throw new DomainException("Исполнителя нет в заявке.");
		unset($this->users['performer'][$user->get_id()]);
	}

	public function remove_manager(data_user $user){
		if(!array_key_exists($user->get_id(), $this->users['manager']))
			throw new DomainException("Исполнителя нет в заявке.");
		unset($this->users['manager'][$user->get_id()]);
	}

	public function add_work(data_query2work $work){
		if(array_key_exists($work->get_id(), $this->works))
			throw new DomainException("Работа уже добавлен в заявку.");
		$this->works[$work->get_id()] = $work;
	}

	public function remove_work(data_query2work $work){
		if(!array_key_exists($work->get_id(), $this->works))
			throw new DomainException("Работа не была в заявке.");
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

	public function get_comments(){
		return $this->comments;
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
		if($id > 4294967295 OR $id < 1)
      throw new DomainException('Идентификатор заявки задан не верно.');
		$this->id = $id;
	}

	public function set_number($number){
		if(!preg_match('/^[0-9]{1,6}$/', $number))
        throw new DomainException('Номер заявки задан не верно.');
		$this->number = (int) $number;
	}

	public function set_time_open($time){
		if($time < 1)
      throw new DomainException('Время открытия заявки задано не верно.');
		$this->time_open = (int) $time;
	}

	public function set_close_reason($reason){
		if(!preg_match('|^[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]{0,65535}$|u', $reason))
      throw new DomainException('Описание заявки заданы не верно.');
		$this->close_reason = (string) $reason;
	}

	public function set_department(data_department $department){
		$this->department = $department;
	}

	public function set_initiator($initiator){
		if(!in_array($initiator, self::$initiator_list, true))
      throw new DomainException('Инициатор заявки задан не верно.');
		$this->initiator = $initiator;
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
		if(!preg_match('|^[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]{0,65535}$|u', $description))
      throw new DomainException('Описание заявки заданы не верно.');
		$this->description = $description;
	}

	public function set_payment_status($status){
		if(!in_array($status, self::$payment_status_list, true))
      throw new DomainException('Статус оплаты заявки задан не верно.');
		$this->payment_status = (string) $status;
	}

	public function set_status($status){
		if(!in_array($status, self::$status_list, true))
      throw new DomainException('Статус заявки задан не верно.');
		$this->status = $status;
	}

	public function set_time_close($time){
		if(!in_array($this->status, ['open', 'working'], true)){
			if( $time < $this->time_open)
				throw new DomainException('Время закрытия заявки не может быть меньше времени открытия.');
			if($time < $this->time_work)
				throw new DomainException('Время закрытия заявки не может быть меньше времени передачи в работу.');
		}
		$this->time_close = $time;
	}

	public function set_time_work($time){
		if($this->time_open > $time)
			throw new DomainException('Время закрытия заявки не может быть меньше времени открытия.');
		$this->time_work = $time;
	}

	public function set_warning_status($status){
		if(!in_array($status, self::$warning_status_list, true))
      throw new DomainException('Статус ворнинга заявки задан не верно.');
		$this->warning_status = (string) $status;
	}
}