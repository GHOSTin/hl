<?php
class verify_group{

    /**
    * Верификация идентификатора группы.
    */
    public static function id(data_group $group){
        if($group->id < 1)
            throw new e_model('Идентификатор группы задан не верно.');
    }

    /**
    * Верификация названия группы.
    */
    public static function name(data_group $group){
        if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-яА-Я0-9]+$/u', $group->name))
           throw new e_model('Название группы задано не верно.');
    }
}