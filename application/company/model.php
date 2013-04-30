<?php
class model_company{

	public static function create_company(data_company $company, data_user $current_user){
		self::verify_company_status($company);
		self::verify_company_name($company);
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
			throw new e_model('Проблемы при создании компании.');
		$stm->closeCursor();
		return $company;
	}
	
	private static function get_insert_id(){
		$sql = "SELECT MAX(`id`) as `max_company_id` FROM `companies`";
		$stm = db::get_handler()->query($sql);
		if($stm == false)
			throw new e_model('Проблема при опредении следующего company_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего company_id.');
		$company_id = (int) $stm->fetch()['max_company_id'] + 1;
		$stm->closeCursor();
		return $company_id;
	}
	/**
	* Верификация идентификатора компании
	*/
	public static function verify_company_id(data_company $company){
		if($company->id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация статуса компании
	*/
	public static function verify_company_status(data_company $company){
		if(empty($company->status))
			throw new e_model('Статус компании задан не верно.');
	}
	/**
	* Верификация названия компании
	*/
	public static function verify_company_name(data_company $company){
		if(empty($company->name))
			throw new e_model('Название компании задано не верно.');
	}
}