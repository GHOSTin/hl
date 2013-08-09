<?php
class verify_group{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_group $group){
        $company = new data_company();
        $company->id = $group->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора группы.
    */
    public static function id(data_group $group){
        if(!preg_match('/^[0-9]{1,5}$/', $group->id))
            throw new e_model('Идентификатор группы задан не верно.');
        if($group->id > 65535 OR $group->id < 1)
            throw new e_model('Идентификатор группы задан не верно.');
    }

    /**
    * Верификация названия группы.
    */
    public static function name(data_group $group){
        if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{1,19}$/u', $group->name))
           throw new e_model('Название группы задано не верно.');
    }

    /**
    * Верификация статуса группы.
    */
    public static function status(data_group $group){
        if(!in_array($group->status, ['false', 'true']))
            throw new e_model('Статус группы задан не верно.');
    }
}