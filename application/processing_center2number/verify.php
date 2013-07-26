<?php
class verify_processing_center2number{
    
    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_processing_center2number $c2n){
        if($c2n->company_id < 1)
            throw new e_model('Идентификатор компании задан не верно.');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function identifier(data_processing_center2number $c2n){
        if(!preg_match('/^[а-яА-Яa-zA-Z0-9]+$/u', $c2n->identifier))
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }
    
    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function number_id(data_processing_center2number $c2n){
        if($c2n->number_id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }

    /**
    * Верификация идентификатора процессингового центра.
    */
    public static function processing_center_id(data_processing_center2number $c2n){
        if($c2n->processing_center_id < 1 OR $c2n->processing_center_id > 255)
            throw new e_model('Идентификатор процессингового центра задан не верно.');
    }
}