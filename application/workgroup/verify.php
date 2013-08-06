<?php
class verify_workgroup{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_workgroup $workgroup){
        $company = new data_company();
        $company->id = $workgroup->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора группы работ.
    */
    public static function id(data_workgroup $workgroup){
        if(!preg_match('/^[0-9]{1,5}$/', $workgroup->id))
            throw new e_model('Идентификатор группы работ задан не верно.');
    }

    /**
    * Верификация названия группы работ.
    */
    public static function name(data_workgroup $workgroup){
        if(!preg_match('/^[А-Я][а-я ]+$/u', $workgroup->name))
            throw new e_model('Название группы работ не удовлетворяет "а-яА-Я".');
    }

    /**
    * Верификация статуса группы работ.
    */
    public static function status(data_workgroup $workgroup){
        if(!in_array($workgroup->status, ['active', 'deactive']))
            throw new e_model('Статус группы работ задан не верно.');
    }
}