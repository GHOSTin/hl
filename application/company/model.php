<?php
class model_company{
	public static function create_company(data_company $company, data_user $current_user){
		try{
			if(empty($company->status) OR empty($company->name))
				throw new exception('Не все параметры заданы правильно.');
			$company_id = self::get_insert_id();
			if($company_id === false)
				return false;
				$company->id = $company_id;
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
			$stm->bindValue(':smslogin', 'smslogin');
			$stm->bindValue(':smspassword', 'smspassword');
			$stm->bindValue(':smssender', 'smssender');
			if($stm->execute() === false)
				return false;
				return $company;
			$stm->closeCursor();
		}catch(exception $e){
			die($e->getMessage());
			throw new exception('Проблемы при создании компании.');
		}
	}

// 	CREATE TABLE IF NOT EXISTS `companies` (
//   `id` TINYINT(3) UNSIGNED NOT NULL,
//   `status` ENUM('true','false') NOT NULL DEFAULT 'true',
//   `name` VARCHAR(255) NOT NULL,
//   `smslogin` VARCHAR(255) NOT NULL,
//   `smspassword` VARCHAR(255) NOT NULL,
//   `smssender` VARCHAR(255) NOT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_company_id` FROM `companies`";
			$stm = db::get_handler()->query($sql);
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_company_id'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего company_id.');
		}
	}	
}