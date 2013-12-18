<?php
class model_company{

	/**
	* Создает новую компанию
	*/
	public static function create_company(data_company $company, data_current_user $user){
		$company->id = self::get_insert_id();
		$company->smslogin = 'smslogin';
		$company->smspassword = 'smspassword';
		$company->smssender = 'smssender';
		$company->verify('id', 'status', 'name', 'smslogin', 'smspassword', 'smssender');
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
	* Возвращает список компаний
	*/
	public static function get_companies(){
		return (new mapper_company(di::get('pdo')))->find_all();
	}
}