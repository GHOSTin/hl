<?php

use \boxxy\classes\di;

class model_query{

	public function __construct(){
		if(empty($_SESSION['query'])){
			$this->init_default_params();
		}
	}

	public function get_params(){
		return $_SESSION['query'];
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

	public function get_houses_by_street($street_id){
		$em = di::get('em');
		if(!empty($_SESSION['query']['r_departments'])){
			return $em->getRepository('data_house')
				->findBy(['department' => $_SESSION['query']['r_departments'],
					'street' => $street_id], ['number' => 'ASC']);
		}else
			return $em->getRepository('data_house')
				->findBy(['street' => $street_id], ['number' => 'ASC']);
	}

	public function init_default_params(){
		$time = getdate();
		$_SESSION['query']['departments'] = di::get('user')->get_profile('query')
			->get_restrictions()['departments'];
		$_SESSION['query']['time_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$_SESSION['query']['time_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
		$_SESSION['query']['status'] = ['open', 'close', 'reopen', 'working'];
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
		$streets = $this->get_streets();
		$s = [];
		if(!empty($streets))
			foreach($streets as $street){
					$s[] = $street->get_id();
			}
		if(!in_array((int) $id, $s, true)){
			$_SESSION['query']['departments'] = [];
			$_SESSION['query']['streets'] = [];
			$_SESSION['query']['houses'] = [];
		}else{
			$houses = $this->get_houses_by_street($id);
			$h = [];
			if(!empty($houses))
				foreach($houses as $house)
					$h[] = $house->get_id();
			$_SESSION['query']['departments'] = [];
			$_SESSION['query']['streets'] = [$id];
			$_SESSION['query']['houses'] = $h;
		}
	}

	public function set_time($time){
		$time = getdate($time);
		$_SESSION['query']['time_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$_SESSION['query']['time_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
	}

	public function set_house($id){
		if($id > 0){
			$house = di::get('em')->find('data_house', $id);
			if(is_null($house))
				$_SESSION['query']['houses'] = [];
			else
				$_SESSION['query']['houses'] = [$house->get_id()];
		}else
			$_SESSION['query']['houses'] = [];
	}

	public function set_work_type($id){
		$wt = di::get('em')->find('data_workgroup', $id);
		if(is_null($wt)){
			$_SESSION['query']['work_types'] = [];
		}else{
			$_SESSION['query']['work_types'] = [$wt->get_id()];
		}
	}
}