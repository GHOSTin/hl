<?php
class model_company{
	public static function create_company(data_company $company, data_user $current_user){
		try{
			if(empty($company->status) OR empty($company->name))
				throw new exception('Не все параметры заданы правильно.');
			$company->id = self::get_insert_id();
			$company->smslogin = 'smslogin';
			$company->smspassword = 'smspassword';
			$company->smssender = 'smssender';
			$sql = "INSERT INTO `companies` (
						`id`, `status`, `name`, `smslogin`, `smspassword`, `smssender`
					) VALUES (
						:company_id, :status, :name, :smslogin, :smspassword, 
						:smssender 
					);";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $company->id);
			$stm->bindValue(':status', $company->status);
			$stm->bindValue(':name', $company->name);
			$stm->bindValue(':smslogin', $company->smslogin);
			$stm->bindValue(':smspassword', $company->smspassword);
			$stm->bindValue(':smssender', $company->smssender);
			if($stm->execute() == false)
				throw new exception('Проблемы при создании компании.');
			$stm->closeCursor();
			return $company;
		}catch(exception $e){
			throw new exception('Проблемы при создании компании.');
		}
	}
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_company_id` FROM `companies`";
			$stm = db::get_handler()->query($sql);
			if($stm == false)
				throw new exception('Проблема при опредении следующего company_id.');
			if($stm->rowCount() !== 1)
				throw new exception('Проблема при опредении следующего company_id.');
			$company_id = (int) $stm->fetch()['max_company_id'] + 1;
			$stm->closeCursor();
			return $company_id;
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего company_id.');
		}
	}	
}