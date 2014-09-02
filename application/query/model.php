<?php
class model_query{

	private $pdo;
	private $params = [];
	private $restrictions = [];

	public function __construct(){
	  $profile = di::get('profile');
	  if((string) $profile === 'query')
	  	$this->restrictions = $profile->get_restrictions();
    if(!empty($_SESSION['model']) AND $_SESSION['model']['model'] === 'query'){
    	$this->params = $_SESSION['model']['params'];
    }else{
    	$_SESSION['model']['model'] = 'query';
    	$this->init_params();
	  }
	}

	public function init_params(){
  	$time = getdate();
  	$time = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
    $this->params['time_open_begin'] = $time;
    $this->params['time_open_end'] = $time  + 86359;
    $this->params['status'] = null;
    $this->params['street'] = null;
    $this->params['house'] = null;
    $this->params['work_type'] = null;
    $this->params['department'] = null;
		if(!empty($this->restrictions['departments']))
			$this->params['department'] = $this->restrictions['departments'];
		else
			$this->params['department'] = [];
    $_SESSION['model']['params'] = $this->params;
	}

	public function get_params(){
		return $this->params;
	}

	public function set_param($param, $value){
		if(!array_key_exists($param, $this->params))
			throw new RuntimeException('Не существует такого параметра.');
		$this->params[$param] = $value;
		$_SESSION['model']['params'] = $this->params;
	}

	public function set_status($status){
		if(!in_array($status, ['open', 'close', 'reopen', 'working'], true))
			$this->set_param('status', null);
		else
			$this->set_param('status', $status);
	}

	public function set_department($id){
		if($id > 0){
			if(!empty($this->restrictions['departments']))
				if(!in_array($id, $this->restrictions['departments']))
					throw new RuntimeException('Участок не может быть добавлен.');
			$department = di::get('em')->find('data_department', $id);
			$this->set_param('department', [$department->get_id()]);
		}else
		if(!empty($this->restrictions['departments']))
			$this->set_param('department', $this->restrictions['departments']);
		else
			$this->set_param('department', []);
	}

	// test
	public function set_street($id){
		if($id > 0){
			$street = di::get('em')->find('data_street', $id);
			$this->set_param('street', $street->get_id());
		}else
			$this->set_param('street', null);
	}

	// test
	public function set_time_open_begin($time){
		$this->set_param('time_open_begin', (int) $time);
	}

	// test
	public function set_time_open_end($time){
		$this->set_param('time_open_end', (int) $time);
	}

	// test
	public function set_house($id){
		if($id > 0){
			$house = di::get('em')->find('data_house', $id);
			$this->set_param('house', $house->get_id());
		}else
			$this->set_param('house', null);
	}

	// test
	public function set_work_type($id){
		if($id > 0){
			$work_type = di::get('model_query_work_type')
				->get_query_work_type($id);
			$this->set_param('work_type', $work_type->get_id());
		}else
			$this->set_param('work_type', null);
	}

	public function create_query($initiator, $id, $description, $work_type,
										$contact_fio, $contact_telephone, $contact_cellphone){
		try{
			$pdo = di::get('pdo');
			if(!$pdo->inTransaction()){
				$pdo->beginTransaction();
				$trans = true;
			}
			$time = time();
			$q_array = ['status' => 'open', 'payment_status' => 'unpaid',
				'warning_status' => 'normal', 'time_open' => $time,
				'time_work' => $time, 'initiator' => $initiator,
				'description' => $description, 'contact_fio' => $contact_fio,
				'contact_telephone' => $contact_telephone,
				'contact_cellphone' => $contact_cellphone
				];
			if($initiator === 'house'){
				$house = di::get('em')->find('data_house', $id);
				$numbers = $house->get_numbers();
			}elseif($initiator === 'number'){
				$number = di::get('em')->find('data_number', $id);
				$house = di::get('em')->find('data_house', $number->get_flat()->get_house()->get_id());
				$numbers[] = $number;
			}
			$q_array['house'] = $house;
			$q_array['department'] = $house->get_department();
			$q_array['type'] = (new model_query_work_type)
				->get_query_work_type($work_type);
			$mapper = di::get('mapper_query');
			$q_array['id'] = $mapper->get_insert_id();
			$q_array['number'] = $mapper->get_insert_query_number($time);
			$query = di::get('factory_query')->build($q_array);
      $query = $mapper->insert($query);
			if(!empty($numbers))
				foreach($numbers as $number)
					$query->add_number($number);
      (new mapper_query2number($query))->update();
			$user = di::get('user');
      $query->add_creator($user);
      $query->add_manager($user);
      $query->add_observer($user);
      (new mapper_query2user($query))->update_users();
      if($trans)
				$pdo->commit();
			return $query;
		}catch(exception $e){
			die($e->getMessage());
			$pdo->rollBack();
			if($e instanceof RuntimeException)
				throw new RuntimeException($e->getMessage());
			else
				throw new RuntimeException('Ошибка при создании заявки.');
		}
	}
}