<?php
class model_processing_center2number{

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