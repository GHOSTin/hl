<?php
class model_user{

	public static function get_user($args){
		$user_id = $args['user_id'];
		if(empty($user_id))
			return false;
		$sql = "SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
				`users`.`username`, `users`.`firtsname`, `users`.`lastname`,
				`users`.`midlename`, `users`.`password`, `users`.`telephone`,
				`users`.`cellphone`
				FROM `users`
				WHERE `users`.`id` = ".$user_id;

		try{
			$stm = db::pdo()->query($sql);
			$stm->prepare($sql)->execute();
			/*
			$row = $stm->fetch();
			if($row !== false)
				return model_query::build_query_object($row);
				return false;*/
		}catch(PDOException $e){
			die($e);
			return false;
		}
		
	}
}