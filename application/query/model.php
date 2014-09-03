<?php
class model_query{

	private $params = [];
	private $restrictions = [];

	public function __construct(){
	  // $profile = di::get('profile');
	  // if((string) $profile === 'query')
	  // 	$this->restrictions = $profile->get_restrictions();
   //  if(!empty($_SESSION['model']) AND $_SESSION['model']['model'] === 'query'){
   //  	$this->params = $_SESSION['model']['params'];
   //  }else{
   //  	$_SESSION['model']['model'] = 'query';
   //  	$this->init_params();
	  // }
	  		// if($params['street'] > 0){
		// 	$street = di::get('em')->find('data_street', $params['street']);
		// 	$houses = $street->get_houses();
		// }else
		// 	$houses = [];
		if(!empty($_SESSION['query']['time']))
			$time = getdate($_SESSION['query']['time']);
		else
	  	$time = getdate();
		$this->params['time_open_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$this->params['time_open_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
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

	public function get_queries(){
		return di::get('em')->getRepository('data_query')
			->findByParams($this->params);
	}

	public function get_timeline(){
		$time = getdate($this->params['time_open_begin']);
		return mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']);
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

	public function set_street($id){
		if($id > 0){
			$street = di::get('em')->find('data_street', $id);
			$this->set_param('street', $street->get_id());
		}else
			$this->set_param('street', null);
	}

	public function set_time($time){
		$time = getdate($time);
		$this->params['time_open_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$this->params['time_open_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
		$_SESSION['query']['time'] = $this->params['time_open_begin'];
	}

	public function set_house($id){
		if($id > 0){
			$house = di::get('em')->find('data_house', $id);
			$this->set_param('house', $house->get_id());
		}else
			$this->set_param('house', null);
	}

	public function set_work_type($id){
		if($id > 0){
			$work_type = di::get('model_query_work_type')
				->get_query_work_type($id);
			$this->set_param('work_type', $work_type->get_id());
		}else
			$this->set_param('work_type', null);
	}
}