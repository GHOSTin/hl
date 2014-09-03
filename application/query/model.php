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
		if(empty($_SESSION['query']['departments'])){
			$this->init_default_params();
		}
	}

	public function get_queries(){
		return di::get('em')->getRepository('data_query')
			->findByParams($_SESSION['query']);
	}

	public function get_departments(){
		if(!empty($_SESSION['query']['r_departments']))
			return di::get('em')->getRepository('data_department')
				->findByid($_SESSION['query']['r_departments'], ['name' => 'ASC']);
		else
			return di::get('em')->getRepository('data_department')
				->findBy([], ['name' => 'ASC']);
	}

	public function get_streets(){
		$em = di::get('em');
		if(!empty($_SESSION['query']['r_departments'])){
			$houses = $em->getRepository('data_house')
				->findBy(['department' => $_SESSION['query']['r_departments']]);
			$streets = [];
			if(!empty($houses))
				foreach($houses as $house)
					$streets[] = $house->get_street()->get_id();
			return $em->getRepository('data_street')
				->findByid($streets, ['name' => 'ASC']);
		}else
			return $em->getRepository('data_street')
				->findBy([], ['name' => 'ASC']);
	}

	public function init_default_params(){
		$_SESSION['query']['departments'] = di::get('user')->get_profile('query')
			->get_restrictions()['departments'];
		$_SESSION['query']['time'] = time();
		$_SESSION['query']['status'] = ['open', 'close', 'reopen', 'working'];
		$_SESSION['query']['wt'] = [];
		$_SESSION['query']['r_departments'] = di::get('user')->get_profile('query')
			->get_restrictions()['departments'];
	}

	public function get_timeline(){
		$time = getdate($_SESSION['query']['time']);
		return mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']);
	}

	public function get_filter_values(){
		if(count($_SESSION['query']['status']) === 1)
			$params['status'] = $_SESSION['query']['status'][0];
		else
			$params['status'] = null;
		if(count($_SESSION['query']['departments']) === 1)
			$params['department'] = $_SESSION['query']['departments'][0];
		else
			$params['department'] = null;
		if(count($_SESSION['query']['work_types']) === 1)
			$params['work_type'] = $_SESSION['query']['work_types'][0];
		else
			$params['work_type'] = null;
		return $params;
	}

	public function set_status($status){
		if(in_array($status, ['open', 'close', 'reopen', 'working'], true)){
			$_SESSION['query']['status'] = [$status];
		}else{
			$_SESSION['query']['status'] = ['open', 'close', 'reopen', 'working'];
		}
	}

	public function set_department($id){
		$department = di::get('em')->find('data_department', $id);
		if(is_null($department)){
			if(!empty($_SESSION['query']['r_departments']))
				$dep = $_SESSION['query']['r_departments'];
		}else{
			if(!empty($_SESSION['query']['r_departments'])){
				if(!in_array($id, $_SESSION['query']['r_departments']))
					throw new RuntimeException('Участок не может быть добавлен.');
				$dep = [$id];
			}else{
				$dep = [$id];
			}
		}
		$_SESSION['query']['departments'] = $dep;
		$_SESSION['query']['streets'] = [];
		$_SESSION['query']['houses'] = [];
	}

	public function set_street($id){
		if($id > 0){
			$street = di::get('em')->find('data_street', $id);
			$this->set_param('street', $street->get_id());
		}else
			$this->set_param('street', null);
	}

	public function set_time($time){
		$_SESSION['query']['time'] = $time;
	}

	public function set_house($id){
		if($id > 0){
			$house = di::get('em')->find('data_house', $id);
			$this->set_param('house', $house->get_id());
		}else
			$this->set_param('house', null);
	}

	public function set_work_type($id){
		$wt = di::get('em')->find('data_query_work_type', $id);
		if(is_null($wt)){
			$_SESSION['query']['work_types'] = [];
		}else{
			$_SESSION['query']['work_types'] = [$wt->get_id()];
		}
	}
}