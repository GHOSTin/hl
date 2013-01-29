<?php
class model_query{

	public static function build_query_object($args){
		#print_r($args);
		$query = new data_query();
		$query->id = (int) $args['id'];
		$query->status = (string) $args['status'];
		$query->initiator = (string) $args['initiator-type'];
		$query->payment_status = (string) $args['payment-status'];
		$query->warning_status = (string) $args['warning-type'];
		$query->department_id = (string) $args['department_id'];
		$query->house_id = (string) $args['house_id'];
		$query->close_reason_id = (string) $args['close_reason_id'];

		$query->description = (string) $args['description-open'];
		$query->number = (string) $args['querynumber'];

		return $query;
		var_dump($query);
		exit();
	}	

	public static function get_query($args){
		$query_id = $args['query_id'];
		if(empty($query_id))
			return false;
		$sql = "SELECT *
				FROM `queries`
				WHERE `id` = ".$query_id;
		try{
			$stm = db::pdo()->query($sql);
			$row = $stm->fetch();
			if($row !== false)
				return model_query::build_query_object($row);
				return false;
		}catch(PDOException $e){
			return false;
		}
	}

	public static function get_queries($args){

		$sql = "SELECT *
				FROM `queries`
				WHERE `opentime` > ".(time() - 86400*20);
		try{
			$stm = db::pdo()->query($sql);
			
			while($row = $stm->fetch())
				$result[$row['id']] = model_query::build_query_object($row);
			return $result;
		}catch(PDOException $e){
			return false;
		}
	}	
}
