<?php
class verify_query_work_type{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_query_work_type $query_work_type){
        $company = new data_company();
        $company->id = $query_work_type->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора типа работа заявки.
    */
    public static function id(data_query_work_type $query_work_type){
        if($query_work_type->id < 1)
            throw new e_model('Идентификатор типа заявки задан не верно.');
    }

    /**
    * Верификация названия типа работ заявки.
    */
    public static function name(data_query_work_type $query_work_type){
        if(!preg_match('/^[а-яА-Я]+$/u', $query_work_type->name))
            throw new e_model('Название типа работ задано не верно.');
    }

    /**
    * Верификация статуса типа работ заявки.
    */
    public static function status(data_query_work_type $query_work_type){
        if(in_array($query_work_type->status, ['true', 'false']))
            throw new e_model('Статус типа работ задан не верно.');
    }
}