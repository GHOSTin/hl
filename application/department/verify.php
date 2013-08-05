<?php
class verify_department{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_department $department){
        $company = new data_company();
        $company->id = $department->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора участка.
    */
    public static function id(data_department $department){
        if($department->id < 1)
            throw new e_model('Идентификатор участка задан не верно.');
    }

    /**
    * Верификация названия участка.
    */
    public static function name(data_department $department){
        if(empty($department->name))
            throw new e_model('Название участка задано не верно.');
    }

    /**
    * Верификация статуса участка.
    */
    public static function verify_status(data_department $department){
        if(!in_array($department->status, ['true', 'false']))
            throw new e_model('Статус участка задан не верно.');
    }
}