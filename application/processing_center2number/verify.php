<?php
class verify_processing_center2number{
    
    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_processing_center2number $c2n){
        $company = new data_company();
        $company->id = $c2n->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function identifier(data_processing_center2number $c2n){
        if(!preg_match('/^[а-яА-Яa-zA-Z0-9]{0,20}$/u', $c2n->identifier))
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }
    
    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function number_id(data_processing_center2number $c2n){
        $number = new data_number();
        $number->id = $c2n->number_id;
        $number->verify('id');
    }

    /**
    * Верификация идентификатора процессингового центра.
    */
    public static function processing_center_id(data_processing_center2number $c2n){
        $center = new data_processing_center();
        $center->id = $c2n->processing_center_id;
        $center->verify('id');
    }
}