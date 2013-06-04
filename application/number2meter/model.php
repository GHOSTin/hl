<?php
class model_number2meter{

    /*
    * Возвращает список счетчиков лицевого счета
    */
    public static function get_number2meters(data_company $company, data_number $number,
                                                data_meter $meter){
        model_company::verify_id($company);
        $number->verify('id');
        $sql = new sql();
        $sql->query("SELECT `meters`.`id` as `meter_id`,
                        `meters`.`name`, `meters`.`rates`,
                        `number2meter`.`service`, `number2meter`.`serial`
                    FROM `meters`, `number2meter`
                    WHERE `number2meter`.`company_id` = :company_id
                    AND `meters`.`company_id` = :company_id
                    AND `number2meter`.`number_id` = :number_id
                    AND `meters`.`id` = `number2meter`.`meter_id`");
        $sql->bind(':number_id', $number->id, PDO::PARAM_INT);
        $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
        if(!empty($meter->serial)){
            $meter->verify('serial');
            $sql->query("AND `number2meter`.`serial` = :serial");
            $sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
        }
        return $sql->map(new data_number2meter(), 'Проблема при при выборке счетчиков лицевого счета.');
    }
}