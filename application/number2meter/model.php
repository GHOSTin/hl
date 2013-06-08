<?php
class model_number2meter{

    /*
    * Возвращает список счетчиков лицевого счета
    */
    public static function get_number2meters(data_company $company, data_number2meter $data){
        model_company::verify_id($company);
        $data->verify('number_id');
        $sql = new sql();
        $sql->query("SELECT `number2meter`.`meter_id`, `number2meter`.`number_id`,
                        `meters`.`name`, `meters`.`rates`, `meters`.`capacity`,
                        `number2meter`.`service`, `number2meter`.`serial`
                    FROM `meters`, `number2meter`
                    WHERE `number2meter`.`company_id` = :company_id
                    AND `meters`.`company_id` = :company_id
                    AND `meters`.`company_id` = :company_id
                    AND `number2meter`.`number_id` = :number_id
                    AND `meters`.`id` = `number2meter`.`meter_id`");
        $sql->bind(':number_id', $data->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
        if(!empty($data->meter_id)){
            $data->verify('meter_id');
            $sql->query("AND `number2meter`.`meter_id` = :meter_id");
            $sql->bind(':meter_id', $data->meter_id, PDO::PARAM_INT);
        }
        if(!empty($data->serial)){
            $data->verify('serial');
            $sql->query("AND `number2meter`.`serial` = :serial");
            $sql->bind(':serial', $data->serial, PDO::PARAM_STR);
        }
        return $sql->map(new data_number2meter(), 'Проблема при при выборке счетчиков лицевого счета.');
    }
}