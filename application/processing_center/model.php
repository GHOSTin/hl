<?php
class model_processing_center{
    /**
    * Возвращает список процессинговых центров
    * @return list object data_processing_center
    */
    public static function get_processing_centers(data_processing_center $center){
        $sql = new sql();
        $sql->query("SELECT `id`, `name` FROM `processing_centers`");
        if(!empty($center->id)){
            //$center->verify('id');
            $sql->query(" WHERE `id` = :id");
            $sql->bind(':id', $center->id, PDO::PARAM_INT);
        }
        $sql->query(" ORDER BY `name`");
        return $sql->map(new data_processing_center(), 'Проблема при выборке расчетных центров.');
    }
}