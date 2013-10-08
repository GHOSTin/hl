<?php
class verify_workgroup{

    /**
    * Верификация идентификатора группы работ.
    */
    public static function id(data_workgroup $workgroup){
        if(!preg_match('/^[0-9]{1,5}$/', $workgroup->get_id()))
            throw new e_model('Идентификатор группы работ задан не верно.');
        if($workgroup->get_id() > 65535 OR $workgroup->get_id() < 1)
            throw new e_model('Идентификатор группы работ задан не верно.');
    }

    /**
    * Верификация названия группы работ.
    */
    public static function name(data_workgroup $workgroup){
        if(!preg_match('/^[А-Я][а-я ]{2,99}$/u', $workgroup->get_name()))
            throw new e_model('Название группы работ не удовлетворяет "а-яА-Я".');
    }

    /**
    * Верификация статуса группы работ.
    */
    public static function status(data_workgroup $workgroup){
        if(!in_array($workgroup->get_status(), ['active', 'deactive']))
            throw new e_model('Статус группы работ задан не верно.');
    }
}