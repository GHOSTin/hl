<?php
class verify_meter2data{

    /**
    * Верификация времени передачи показания.
    */
    public static function timestamp(data_meter2data $data){
        if($data->timestamp < 1)
            throw new e_model('Время передачи показания задано не верно.');
    }

    /**
    * Верификация способа передачи показания.
    */
    public static function way(data_meter2data $data){
        $ways = ['answerphone', 'telephone', 'fax', 'personally'];
        if(!in_array($data->way, $ways))
            throw new e_model('Спсоб передачи показания задан не верно.');
    }
}