<?php
/*
* Связь с таблицей `meter2data`.
*/
final class data_meter2data extends data_object{

	public $time;
	public $value;
    public $comment;

    public function __construct(){
        if(empty($this->value))
            $this->value = [];
        else
            $this->value = explode(';', $this->value);
    }
}