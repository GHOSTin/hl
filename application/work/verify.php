<?php
class verify_work{

    /**
    * Верификация идентификатора работы.
    */
    public static function id(data_work $work){
        if($work->id < 1)
            throw new e_model('Идентификатор работы задан не верно.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_work $work){
        $company = new data_company();
        $company->id = $work->company_id;
        $company->verify('id');
    }

    /**
    * Верификация названия работы.
    */
    public static function verify_name(data_work $work){
        if(!preg_match('/^[а-яА-Я]+$/u', $work->name))
            throw new e_model('Название работы не удовлетворяет "а-яА-Я".');
    }

    /**
    * Верификация статуса работы.
    */
    public static function verify_status(data_work $work){
        if(in_array($work->status, ['true', 'false']))
            throw new e_model('Статус работы задан не верно.');
    }

    /**
    * Верификация идентификатора группы работ.
    */
    public static function verify_workgroup_id(data_work $work){
        $worgroup = new data_workgroup();
        $worgroup->id = $work->workgroup_id;
        $worgroup->verify('id');
    }
}