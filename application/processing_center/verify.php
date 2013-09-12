<?php
class verify_processing_center{

    /**
    * Верификация идентификатора процессингового центра.
    */
    public static function id(data_processing_center $center){
        if(!preg_match('/^[0-9]{1,3}$/', $center->get_id()))
            throw new e_model('Идентификатор процессингового центра задан не верно.');
        if($center->get_id() < 1 OR $center->get_id() > 255)
            throw new e_model('Идентификатор процессингового центра задан не верно.');
    }

    /**
    * Верификация названия процессингового центра.
    */
    public static function name(data_processing_center $center){
        if(!preg_match('/^[А-Яа-я0-9 -]{2,20}$/u', $center->get_name()))
            throw new e_model('Название процессингового центра задано не верно.');
    }
}