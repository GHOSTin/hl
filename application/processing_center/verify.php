<?php
class verify_processing_center{

    /**
    * Верификация идентификатора процессингового центра.
    */
    public static function id(data_processing_center $center){
        if($center->id < 1 OR $center->id > 255)
            throw new e_model('Идентификатор процессингового центра задан не верно.');
    }

    /**
    * Верификация названия процессингового центра.
    */
    public static function name(data_processing_center $center){
        if(!preg_match('/^[а-яА-Я0-9 -]+$/u', $center->name))
            throw new e_model('Название процессингового центра задано не верно.');
    }
}