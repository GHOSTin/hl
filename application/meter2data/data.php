<?php
/*
* Связь с таблицей `meter2data`.
*/
final class data_meter2data extends data_object{

	public $time;
	public $value;
    public $comment;
    public $way;
    public $timestamp;

    public function __construct(){
        if(empty($this->value))
            $this->value = [];
        else
            $this->value = explode(';', $this->value);
    }

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_meter2data::$value($this);
    }
}