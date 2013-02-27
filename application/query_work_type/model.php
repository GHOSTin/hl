<?php
class model_query_work_type{
	public static function get_query_work_types(){
		try{
			$sql = "SELECT `id`,`company_id`, `status`, `name`
					FROM `query_worktypes`
					WHERE `company_id` = :company_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $_SESSION['user']->company_id);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query_work_type');
			while($user = $stm->fetch())
				$result[] = $user;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			return false;
		}
	}
}