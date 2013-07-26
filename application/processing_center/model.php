<?php
class model_processing_center{

    /*
    * Создает процессинговый центр.
    */
    public static function create_processing_center(data_processing_center $center){
        $center->verify('name');
        if(count(self::get_processing_centers($center)) !== 0 )
            throw new e_model('Такой процессинговый центр уже существует.');
        $center->id = self::get_insert_id();
        $sql = new sql();
        $sql->query("INSERT INTO `processing_centers` (`id`, `name`)
                    VALUES (:id, :name)");
        $sql->bind(':id', $center->id, PDO::PARAM_INT);
        $sql->bind(':name', $center->name, PDO::PARAM_STR);
        $sql->execute('Проблемы при создании процессингового центра.');
        return $center;
    }

    /**
    * Возвращает следующий для вставки processing_center_id.
    * @return int
    */
    private static function get_insert_id(){
        $sql = new sql();
        $sql->query("SELECT MAX(`id`) as `max_id` FROM `processing_centers`");
        $sql->execute('Проблема при опредении следующего processing_center_id.');
        if($sql->count() !== 1)
            throw new e_model('Проблема при опредении следующего processing_center_id.');
        $user_id = (int) $sql->row()['max_id'] + 1;
        $sql->close();
        return $user_id;
    }

    /**
    * Возвращает список процессинговых центров
    * @return list object data_processing_center
    */
    public static function get_processing_centers(data_processing_center $center){
        $sql = new sql();
        $sql->query("SELECT `id`, `name` FROM `processing_centers`");
        if(!empty($center->id)){
            $center->verify('id');
            $sql->query(" WHERE `id` = :id");
            $sql->bind(':id', $center->id, PDO::PARAM_INT);
        }
        if(!empty($center->name)){
            $center->verify('name');
            $sql->query(" WHERE `name` = :name");
            $sql->bind(':name', $center->name, PDO::PARAM_STR);
        }
        $sql->query(" ORDER BY `name`");
        return $sql->map(new data_processing_center(), 'Проблема при выборке расчетных центров.');
    }

    /*
    * Переименование процессингового центра.
    */
    public static function rename_processing_center(data_processing_center $center, $name){
        $center->verify('id');
        $centers = self::get_processing_centers($center);
        if(count($centers) !== 1)
            throw new e_model('Неожиданное количество процессинговых центров');
        $center = $centers[0];
        self::is_data_processing_center($center);
        $center->name = $name;
        $center->verify('id', 'name');
        $sql = new sql();
        $sql->query("UPDATE `processing_centers` SET `name` = :name
                    WHERE `id` = :id");
        $sql->bind(':id', $center->id, PDO::PARAM_INT);
        $sql->bind(':name', $center->name, PDO::PARAM_STR);
        $sql->execute('Проблемы при переименовании процессингового центра.');
        return $center;
    }

    /**
    * Проверка принадлежности объекта к классу data_processing_center.
    */
    public static function is_data_processing_center($center){
        if(!($center instanceof data_processing_center))
            throw new e_model('Возвращеный объект не является процессинговым центром.');
    }
}