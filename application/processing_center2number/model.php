<?php
class model_processing_center2number{

    /*
    * Добавляет идентификатор.
    */
    public static function add_identifier(data_company $company, data_processing_center2number $c2n){
        $company->verify('id');
        $number = new data_number();
        $number->id = $c2n->number_id;
        if(count(model_number::get_numbers($company, $number)) !== 1)
            throw new e_model('Неожиданное количество лицевых счетов.');
        $center = new data_processing_center();
        $center->id = $c2n->processing_center_id;
        if(count(model_processing_center::get_processing_centers($center)) !== 1)
            throw new e_model('Неожиданное количество процессинговых центров.');
        $center = new data_processing_center2number();
        $center->number_id = $c2n->number_id;
        $center->processing_center_id = $c2n->processing_center_id;
        if(count(self::get_processing_centers($company, $center)) > 0)
            throw new e_model('Уже есть идентификатор на этом расчетном центре.');
        $c2n->company_id = $company->id;
        $c2n->verify('number_id', 'identifier', 'processing_center_id', 'company_id');
        $sql = new sql();
        $sql->query("INSERT INTO `processing_center2number`(`company_id`, `processing_center_id`,
            `number_id`, `identifier`) VALUES (:company_id, :processing_center_id, :number_id, :identifier)");
        $sql->bind(':company_id', $c2n->company_id, PDO::PARAM_INT);
        $sql->bind(':processing_center_id', $c2n->processing_center_id, PDO::PARAM_INT);
        $sql->bind(':number_id', $c2n->number_id, PDO::PARAM_INT);
        $sql->bind(':identifier', $c2n->identifier, PDO::PARAM_STR);
        return $sql->execute('Проблема при добавлении идентификатора расчетного центра.');
    }

    /*
    * Исключает идентификатор.
    */
    public static function exclude_identifier(data_company $company, data_processing_center2number $c2n){
        $company->verify('id');
        $c2n->company_id = $company->id;
        $c2n->verify('number_id', 'identifier', 'processing_center_id', 'company_id');
        $sql = new sql();
        $sql->query("DELETE FROM `processing_center2number` WHERE `company_id` = :company_id
                    AND `number_id` = :number_id AND `processing_center_id` = :processing_center_id
                    AND `identifier` = :identifier");
        $sql->bind(':company_id', $c2n->company_id, PDO::PARAM_INT);
        $sql->bind(':processing_center_id', $c2n->processing_center_id, PDO::PARAM_INT);
        $sql->bind(':number_id', $c2n->number_id, PDO::PARAM_INT);
        $sql->bind(':identifier', $c2n->identifier, PDO::PARAM_STR);
        return $sql->execute('Проблема при ислючении идентификатора расчетного центра.');
    }

    /*
    * Возвращает связи процессингового центра и лицевого счтеа
    */
    public static function get_processing_centers(data_company $company, data_processing_center2number $c2n){
        $company->verify('id');
        $sql = new sql();
        $sql->query("SELECT `processing_center2number`.`company_id`,
                    `processing_center2number`.`processing_center_id`,
                    `processing_center2number`.`number_id`,
                    `processing_center2number`.`identifier`,
                    `processing_centers`.`name` as `processing_center_name`
                    FROM `processing_center2number`, `processing_centers`
                    WHERE `processing_center2number`.`company_id` = :company_id
                    AND `processing_center2number`.`processing_center_id` = `processing_centers`.`id`");
        $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
        if(!empty($c2n->number_id)){
            $c2n->verify('number_id');
            $sql->query("AND `processing_center2number`.`number_id` = :number_id");
            $sql->bind(':number_id', $c2n->number_id, PDO::PARAM_INT);
        }
        if(!empty($c2n->processing_center_id)){
            $c2n->verify('processing_center_id');
            $sql->query("AND `processing_center2number`.`processing_center_id` = :processing_center_id");
            $sql->bind(':processing_center_id', $c2n->processing_center_id, PDO::PARAM_INT);
        }
        $sql->query(" ORDER BY `processing_centers`.`name`");
        return $sql->map(new data_processing_center2number(), 'Проблема при при выборке расчетных центров.');
    }
}