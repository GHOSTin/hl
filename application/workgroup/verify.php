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
        if($workgroup->id < 1)
            throw new e_model('Идентификатор группы работ задан не верно.');
    }

    /**
    * Верификация названия группы работ.
    */
    public static function verify_name(data_workgroup $workgroup){
        if(!preg_match('/^[а-яА-Я]+$/u', $workgroup->name))
            throw new e_model('Название группы работ не удовлетворяет "а-яА-Я".');
    }

    /**
    * Верификация статуса группы работ.
    */
    public static function verify_status(data_workgroup $workgroup){
        if(in_array($workgroup->status, ['true', 'false']))
            throw new e_model('Статус группы работ задан не верно.');
    }
}