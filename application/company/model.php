<?php
class model_company{
	/**
	* Создает новую компанию
	* @return object data_company
	*/
	public static function create_company(data_company $company, data_current_user $user){
		$company->id = self::get_insert_id();
		$company->smslogin = 'smslogin';
		$company->smspassword = 'smspassword';
		$company->smssender = 'smssender';
		self::verify_id($company);
		self::verify_status($company);
		self::verify_name($company);
		self::verify_smslogin($company);
		self::verify_smspassword($company);
		self::verify_smssender($company);
		$sql = new sql();
		$sql->query("INSERT INTO `companies` (`id`, `status`, `name`, `smslogin`, 
					`smspassword`, `smssender`) VALUES (:company_id, :status, :name,
					:smslogin, :smspassword, :smssender)");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':status', $company->status, PDO::PARAM_STR);
		$sql->bind(':name', $company->name, PDO::PARAM_STR);
		$sql->bind(':smslogin', $company->smslogin, PDO::PARAM_STR);
		$sql->bind(':smspassword', $company->smspassword, PDO::PARAM_STR);
		$sql->bind(':smssender', $company->smssender, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании компании.');
		$sql->close();
		return $company;
	}
	/**
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	private static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_company_id` FROM `companies`");
		$sql->execute('Проблема при опредении следующего company_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего company_id.');
		$company_id = (int) $sql->row()['max_company_id'] + 1;
		$sql->close();
		return $company_id;
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_id(data_company $company){
		if($company->id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация названия компании.
	*/
	public static function verify_name(data_company $company){
		if(empty($company->name))
			throw new e_model('Название компании задано не верно.');
	}
	/**
	* Верификация статуса компании.
	*/
	public static function verify_status(data_company $company){
		if(empty($company->status))
			throw new e_model('Статус компании задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_company.
	*/
	public static function is_data_company($company){
		if(!($company instanceof data_company))
			throw new e_model('Возвращеный объект не является компанией.');
	}
}