<?php
class model_query{
	/**
	* Возвращает заявку
	* @return false or data_query
	*/
	public static function get_query($args){
		try{
			$query_id = $args['query_id'];
			if(empty($query_id))
				throw new exception('Wrong parametr');
			$sql = "SELECT `queries`.`id`, `queries`.`company_id`,
				`queries`.`status`, `queries`.`initiator-type` as `initiator`,
				`queries`.`payment-status` as `payment_status`,
				`queries`.`warning-type` as `warning_status`,
				`queries`.`department_id`, `queries`.`house_id`,
				`queries`.`query_close_reason_id` as `close_reason_id`,
				`queries`.`query_worktype_id` as `worktype_id`,
				`queries`.`opentime` as `time_open`,
				`queries`.`worktime` as `time_work`,
				`queries`.`closetime` as `time_close`,
				`queries`.`addinfo-name` as `contact_fio`,
				`queries`.`addinfo-telephone` as `contact_telephone`,
				`queries`.`addinfo-cellphone` as `contact_cellphone`,
				`queries`.`description-open` as `description`,
				`queries`.`description-close` as `close_reason`,
				`queries`.`querynumber` as `number`,
				`queries`.`query_inspection` as `inspection`, 
				`houses`.`housenumber` as `house_number`,
				`streets`.`name` as `street_name`
				FROM `queries`, `houses`, `streets`
				WHERE `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`id` = :query_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':query_id', $query_id, PDO::PARAM_INT);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
			$query = $stm->fetch();
			$stm->closeCursor();
			return $query;
		}catch(exception $e){
			return false;
		}
	}
	/**
	* Возвращает заявки
	* @return false or array
	*/
	public static function get_queries($args){
		try{
			$time_open = (time() - 86400*19);
			$sql = "SELECT `queries`.`id`, `queries`.`company_id`,
				`queries`.`status`, `queries`.`initiator-type` as `initiator`,
				`queries`.`payment-status` as `payment_status`,
				`queries`.`warning-type` as `warning_status`,
				`queries`.`department_id`, `queries`.`house_id`,
				`queries`.`query_close_reason_id` as `close_reason_id`,
				`queries`.`query_worktype_id` as `worktype_id`,
				`queries`.`opentime` as `time_open`,
				`queries`.`worktime` as `time_work`,
				`queries`.`closetime` as `time_close`,
				`queries`.`addinfo-name` as `contact_fio`,
				`queries`.`addinfo-telephone` as `contact_telephone`,
				`queries`.`addinfo-cellphone` as `contact_cellphone`,
				`queries`.`description-open` as `description`,
				`queries`.`description-close` as `close_reason`,
				`queries`.`querynumber` as `number`,
				`queries`.`query_inspection` as `inspection`, 
				`houses`.`housenumber` as `house_number`,
				`streets`.`name` as `street_name`
				FROM `queries`, `houses`, `streets`
				WHERE `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `opentime` > :time_open";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':time_open', $time_open, PDO::PARAM_INT, 10);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
			while($query = $stm->fetch())
				$result[$query->id] = $query;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			return false;
		}
	}	
}
