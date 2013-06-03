<?php
class model_number2meter{
    /*
    * Возвращает список счетчиков лицевого счета
    */
    public static function get_number2meters(data_company $company, data_number $number,
                                                data_meter $meter){
        model_company::verify_id($company);
        model_number::verify_id($number);
        $sql = new sql();
        $sql->query("SELECT `meters`.`id` as `meter_id`,
                        `meters`.`name`,
                        `meters`.`rates`,
                        `number2meter`.`service`,
                        `number2meter`.`serial`
                    FROM `meters`, `number2meter`
                    WHERE `number2meter`.`company_id` = :company_id
                    AND `meters`.`company_id` = :company_id
                    AND `number2meter`.`number_id` = :number_id
                    AND `meters`.`id` = `number2meter`.`meter_id`");
        $sql->bind(':number_id', $number->id, PDO::PARAM_INT);
        $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
        if(!empty($meter->serial)){
            model_meter::verify_serial($meter);
            $sql->query("AND `number2meter`.`serial` = :serial");
            $sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
        }
        return $sql->map(new data_number2meter(), 'Проблема при при выборке счетчиков лицевого счета.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function verify_company_id(data_number2meter $number2meter){
        if($number2meter->company_id < 1 OR $number2meter->company_id > 254)
            throw new e_model('Идентификатор компании задан не верно.');
    }

    /**
    * Верификация идентификатора счетчика.
    */
    public static function verify_meter_id(data_number2meter $number2meter){
        if($number2meter->meter_id < 1)
            throw new e_model('Идентификатор счетчика задан не верно.');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function verify_number_id(data_number2meter $number2meter){
        if($number2meter->number_id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }

    /**
    * Верификация заводского номера счетчика.
    */
    public static function verify_serial(data_number2meter $number2meter){
        if(!preg_match('/^[а-яА-Я0-9]+$/u', $number2meter->serial))
            throw new e_model('Заводской номер счетчика задано не верно.');
    }
}