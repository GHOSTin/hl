<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity(repositoryClass="domain\repositories\query")
* @Table(name="queries")
*/
class query{

  use traits\cellphone;

	/**
  * @Id
  * @Column(type="integer")
  * @GeneratedValue
  */
	private $id;

	/**
  * @Column
  */
	private $status;

	/**
  * @Column
  */
	private $initiator;

  /**
  * @ManyToOne(targetEntity="domain\query_type")
  */
	private $query_type;

	/**
  * @ManyToOne(targetEntity="domain\department")
  */
	private $department;

	/**
  * @ManyToOne(targetEntity="domain\house")
  */
	private $house;

	/**
  * @ManyToOne(targetEntity="domain\workgroup")
  * @JoinColumn(name="query_worktype_id", referencedColumnName="id")
  */
	private $work_type;

  /**
  * @OneToOne(targetEntity="domain\number_request", mappedBy="query")
  */
  private $request;

	/**
  * @Column(name="opentime")
  */
	private $time_open;

	/**
  * @Column(name="worktime")
  */
	private $time_work;

	/**
  * @Column(name="closetime", nullable=true)
  */
	private $time_close;

	/**
  * @Column(nullable=true)
  */
	private $contact_fio;

  /**
  * @Column(type="boolean")
  */
  private $visible = false;

	/**
  * @Column(nullable=true)
  */
	private $contact_telephone;

	/**
  * @Column(nullable=true)
  */
	private $contact_cellphone;

	/**
  * @Column
  */
	private $description;

	/**
  * @Column(name="reason", nullable=true)
  */
	private $close_reason;

	/**
  * @Column(name="querynumber")
  */
	private $number;

	/**
   * @ManyToMany(targetEntity="domain\number")
   * @JoinTable(name="query2number",
   * joinColumns={@JoinColumn(name="query_id", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="number_id", referencedColumnName="id")})
   */
	private $numbers;

	private $works;

	/**
  * @OneToMany(targetEntity="domain\query2user", mappedBy="query", cascade="all")
  */
	private $users;

  /**
  * @OneToMany(targetEntity="domain\query2file", mappedBy="query", cascade="all", orphanRemoval=true)
  */
  private $files;

	/**
  * @OneToMany(targetEntity="domain\query2comment", mappedBy="query")
  */
	private $comments;

  /**
  * @Column(type="json_array")
  */
  private $history = [];

	public static $initiator_list = ['number', 'house'];
	public static $status_list = ['open', 'close', 'working', 'reopen'];

  const RE_DESCRIPTION = '|^[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]{0,65535}$|u';

  public function __construct(){
    $time = time();
    $this->numbers = new ArrayCollection();
    $this->comments = new ArrayCollection();
    $this->users = new ArrayCollection();
    $this->files = new ArrayCollection();
    $this->status = 'open';
    $this->time_open = $time;
    $this->time_work = $time;
  }

  public function add_manager(user $user){
    $manager = new query2user($this, $user);
    $manager->set_class('manager');
    $this->users->add($manager);
  }

	public function add_number(number $number){
    if($this->numbers->contains($number))
      throw new DomainException('Лицевой счет уже добавлен в заявку.');
		$this->numbers->add($number);
	}

	public function add_comment(query2comment $comment){
		if($this->comments->contains($comment))
			throw new DomainException('Комментарий уже существует.');
		$this->comments->add($comment);
	}

  public function add_file(file $file){
    if(!in_array($this->status, ['open', 'reopen', 'working']))
      throw new DomainException();
    $q2f = new query2file($this, $file);
    $this->files->add($q2f);
  }

	public function add_user(query2user $user){
		$this->users->add($user);
	}

	public function add_work(query2work $work){
		if($this->works->contains($work))
			throw new DomainException("Работа уже добавлен в заявку.");
		$this->works->add($work);
	}

  public function close(user $user, $time, $reason){
    if(!in_array($this->status, ['working', 'open'], true))
      throw new DomainException('Заявка не может быть закрыта.');
    $this->status = 'close';
    $this->set_time_close($time);
    $this->set_close_reason($reason);
    $context = [
                'Причина закрытия' => $reason
                ];
    $this->add_history_event($user, 'Закрытие заявки', $context);
  }

  public function add_history_event(user $user, $message, array $context = []){
    $this->history[] = [
                        'time' => time(),
                        'message' => $message,
                        'user' => $user->get_log_name(),
                        'context' => $context
                       ];
  }

  public function get_history(){
    return $this->history;
  }

  public function is_visible(){
    return $this->visible;
  }

  public function update_visible(){
    return $this->visible = !$this->visible;
  }

  public function delete_file(query2file $file){
    if(!in_array($this->status, ['open', 'reopen', 'working']))
      throw new DomainException();
    $this->files->removeElement($file);
  }

	public function remove_work(work $w){
		if(!empty($this->works))
			foreach($this->works as $work)
				if($work->get_id() === $w->get_id()){
					$this->works->removeElement($work);
					return $work;
				}
	}

  public function reclose(user $user){
    if($this->status !== 'reopen')
      throw new DomainException();
    $this->status = 'close';
    $this->add_history_event($user, 'Перезакрытие заявки');
  }

  public function reopen(user $user){
    if($this->status !== 'close')
      throw new DomainException();
    $this->status = 'reopen';
    $this->add_history_event($user, 'Переоткрытие заявки');
  }

  public function to_work($time){
    if($this->status !== 'open')
      throw new DomainException();
    $this->status = 'working';
    $this->set_time_work($time);
  }

	public function add_work_type(workgroup $category){
		$this->work_type = $category;
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

  public function get_files(){
    return $this->files;
  }

	public function get_creator(){
		$creators = [];
		if(!empty($this->users))
			foreach($this->users as $user)
				if($user->get_class() === 'creator')
					$creators[] = $user;
		return $creators[0];
	}

	public function get_managers(){
		$managers = [];
		if(!empty($this->users))
			foreach($this->users as $user)
				if($user->get_class() === 'manager')
					$managers[] = $user;
		return $managers;
	}

	public function get_observers(){
		$performers = [];
		if(!empty($this->users))
			foreach($this->users as $user)
				if($user->get_class() === 'observer')
					$performers[] = $user;
		return $performers;
	}

	public function get_performers(){
		$observers = [];
		if(!empty($this->users))
			foreach($this->users as $user)
				if($user->get_class() === 'performer')
					$observers[] = $user;
		return $observers;
	}

	public function get_users(){
		return $this->users;
	}

	public function get_works(){
		return $this->works;
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

	public function get_number(){
		return $this->number;
	}

  public function get_request(){
    return $this->request;
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

	public function get_query_type(){
		return $this->query_type;
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
		$this->time_open = $time;
	}

	public function set_close_reason($reason){
		if(!preg_match('|^[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]{0,65535}$|u', $reason))
      throw new DomainException('Описание заявки заданы не верно.');
		$this->close_reason = (string) $reason;
	}

  public function set_creator(user $user){
    $creator = new query2user($this, $user);
    $creator->set_class('creator');
    $this->users->add($creator);
  }

	public function set_department(department $department){
		$this->department = $department;
	}

	public function set_initiator($initiator){
		if(!in_array($initiator, self::$initiator_list, true))
      throw new DomainException('Инициатор заявки задан не верно.');
		$this->initiator = $initiator;
	}

	public function set_house(house $house){
		$this->house = $house;
	}

	public function set_contact_cellphone($cellphone){
		$this->contact_cellphone = $this->prepare_cellphone($cellphone);
	}

	public function set_contact_fio($fio){
		$this->contact_fio = $fio;
	}

	public function set_contact_telephone($telephone){
    preg_match_all('/[0-9]/', $telephone, $telephone_matches);
		$this->contact_telephone = implode('', $telephone_matches[0]);
	}

	public function set_description($description){
		if(!preg_match(self::RE_DESCRIPTION, $description))
      throw new DomainException('Описание заявки заданы не верно.');
		$this->description = $description;
	}

  public function set_request(number_request $request){
    $this->request = $request;
    $this->request->set_query($this);
  }

	private function set_time_close($time){
		if($time < $this->time_open)
			throw new DomainException('Время закрытия заявки не может быть меньше времени открытия.');
		if($time < $this->time_work)
			throw new DomainException('Время закрытия заявки не может быть меньше времени передачи в работу.');
		$this->time_close = $time;
	}

	public function set_time_work($time){
		if($this->time_open > $time)
			throw new DomainException('Время закрытия заявки не может быть меньше времени открытия.');
		$this->time_work = $time;
	}

	public function set_query_type(query_type $query_type){
		$this->query_type = $query_type;
	}

  public static function new_instance_from_from_array(array $args){
    $query = new query();
    $query->add_work_type($args['category']);
    $query->set_query_type($args['query_type']);
    $query->set_description($args['description']);
    $query->set_contact_fio($args['contact_fio']);
    $query->set_contact_telephone($args['contact_telephone']);
    $query->set_contact_cellphone($args['contact_cellphone']);
    $query->set_number($args['number']);
    return $query;
  }

  public static function new_instance_number_initiator(number $number, array $args){
    $query = self::new_instance_from_from_array($args);
    $house = $number->get_flat()->get_house();
    $query->set_house($house);
    $query->set_department($house->get_department());
    $query->add_number($number);
    $query->set_initiator('number');
    return $query;
  }

  public static function new_instance_house_initiator(house $house, array $args){
    $query = self::new_instance_from_from_array($args);
    $query->set_house($house);
    $query->set_department($house->get_department());
    $query->set_initiator('house');
    return $query;
  }

  public static function new_instance_from_request(number_request $request, array $args){
    $query = self::new_instance_from_from_array($args);
    $number = $request->get_number();
    $house = $number->get_flat()->get_house();
    $query->set_house($house);
    $query->set_department($house->get_department());
    $query->add_number($number);
    $query->set_initiator('number');
    $query->set_request($request);
    return $query;
  }
}