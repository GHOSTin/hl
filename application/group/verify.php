<?php
class verify_group{

    /**
    * Верификация идентификатора группы.
    */
    public static function id(data_group $group){
        if($group->id < 1)
            throw new e_model('Идентификатор группы задан не верно.');
    }
}