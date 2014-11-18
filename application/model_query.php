<?php

use \Pimple\Container;

class model_query{

	private $pimple;

	private static $statuses = ['open', 'close', 'reopen', 'working'];

	public function __construct(Container $pimple){
		$this->pimple = $pimple;
		if(empty($_SESSION['query'])){
			$this->init_default_params();
		}
	}

	public function get_params(){
		return $_SESSION['query'];
	}

	public function get_queries(){
		return $this->pimple['em']->getRepository('data_query')
			->findByParams($_SESSION['query']);
	}

	public function get_departments(){
		if(!empty($_SESSION['query']['r_departments']))
			return $this->pimple['em']->getRepository('data_department')
				->findByid($_SESSION['query']['r_departments'], ['name' => 'ASC']);
		else
			return $this->pimple['em']->getRepository('data_department')
				->findBy([], ['name' => 'ASC']);
	}

	public function get_streets(){
		$em = $this->pimple['em'];
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

	public function get_houses_by_street($street_id){
		$em = $this->pimple['em'];
		if(!empty($_SESSION['query']['r_departments'])){
			$houses = $em->getRepository('data_house')
				->findBy(['department' => $_SESSION['query']['r_departments'],
					'street' => $street_id]);
		}else{
			$houses =  $em->getRepository('data_house')
				->findBy(['street' => $street_id]);
		}
		natsort($houses);
		return $houses;
	}

	public function init_default_params(){
		$time = getdate();
		$_SESSION['query']['departments'] = di::get('user')->get_profile('query')
			->get_restrictions()['departments'];
		$_SESSION['query']['time_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$_SESSION['query']['time_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
		$_SESSION['query']['status'] = self::$statuses;
		$_SESSION['query']['work_types'] = [];
		$_SESSION['query']['r_departments'] = di::get('user')->get_profile('query')
			->get_restrictions()['departments'];
		$_SESSION['query']['streets'] = [];
		$_SESSION['query']['houses'] = [];
	}

	public function get_timeline(){
		return strtotime('+12 hours', $_SESSION['query']['time_begin']);
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
    if(count($_SESSION['query']['streets']) === 1)
		  $params['street'] = $_SESSION['query']['streets'][0];
    else
      $params['streets'] = null;
		if(count($_SESSION['query']['houses']) === 1)
			$params['house'] = $_SESSION['query']['houses'][0];
		else
			$params['house'] = null;
		return $params;
	}

	public function set_status($status){
		if(in_array($status, self::$statuses, true)){
			$_SESSION['query']['status'] = [$status];
		}else{
			$_SESSION['query']['status'] = self::$statuses;
		}
	}

	public function set_department($department_id){
		$department = $this->pimple['em']->find('data_department', $department_id);
		if(is_null($department)){
			if(!empty($_SESSION['query']['r_departments']))
				$departments = $_SESSION['query']['r_departments'];
		}else{
			if(!empty($_SESSION['query']['r_departments'])){
				if(!in_array($department_id, $_SESSION['query']['r_departments']))
					throw new RuntimeException('Участок не может быть добавлен.');
			}else{
				$departments = [$department_id];
			}
		}
		$_SESSION['query']['departments'] = $departments;
		$_SESSION['query']['streets'] = [];
		$_SESSION['query']['houses'] = [];
	}

	public function set_street($street_id){
		$streets = $this->get_streets();
		$s = [];
		if(!empty($streets))
			foreach($streets as $street){
					$s[] = $street->get_id();
			}
		if(!in_array((int) $street_id, $s, true)){
			$_SESSION['query']['departments'] = [];
			$_SESSION['query']['streets'] = [];
			$_SESSION['query']['houses'] = [];
		}else{
			$houses = $this->get_houses_by_street($street_id);
			$h = [];
			if(!empty($houses))
				foreach($houses as $house)
					$h[] = $house->get_id();
			$_SESSION['query']['departments'] = [];
			$_SESSION['query']['streets'] = [$street_id];
			$_SESSION['query']['houses'] = $h;
		}
	}

	public function set_time($time){
		$time = getdate($time);
		$_SESSION['query']['time_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$_SESSION['query']['time_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
	}

	public function set_house($house_id){
		if($house_id > 0){
			$house = $this->pimple['em']->find('data_house', $house_id);
			if(is_null($house))
				$_SESSION['query']['houses'] = [];
			else
				$_SESSION['query']['houses'] = [$house->get_id()];
		}else
			$_SESSION['query']['houses'] = [];
	}

	public function set_work_type($workgroup_id){
		$workgroup = $this->pimple['em']->find('data_workgroup', $workgroup_id);
		if(is_null($workgroup)){
			$_SESSION['query']['work_types'] = [];
		}else{
			$_SESSION['query']['work_types'] = [$workgroup->get_id()];
		}
	}
}