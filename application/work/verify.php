<?php
class verify_work{

    /**
    * Верификация идентификатора работы.
    */
    public static function id(data_work $work){
        if(!preg_match('/^[0-9]{1,5}$/u', $work->id))
            throw new e_model('Идентификатор работы задан не верно.');
        if($work->id > 65535 OR $work->id < 1)
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
    public static function name(data_work $work){
        if(!preg_match('/^[А-Я][а-я ]{2,99}$/u', $work->name))
            throw new e_model('Название работы не удовлетворяет "а-яА-Я".');
    }

    /**
    * Верификация статуса работы.
    */
    public static function status(data_work $work){
        if(!in_array($work->status, ['active', 'deactive']))
            throw new e_model('Статус работы задан не верно.');
    }

    /**
    * Верификация идентификатора группы работ.
    */
    public static function workgroup_id(data_work $work){
        $worgroup = new data_workgroup();
        $worgroup->id = $work->workgroup_id;
        $worgroup->verify('id');
    }
}