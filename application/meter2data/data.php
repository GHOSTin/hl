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
    private $company_id;
    private $number_id;
    private $meter_id;
    private $serial;

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

    public function get_comment(){
        return $this->comment;
    }

    public function get_company_id(){
        return $this->company_id;
    }

    public function get_meter_id(){
        return $this->meter_id;
    }

    public function get_number_id(){
        return $this->number_id;
    }

    public function get_serial(){
        return $this->serial;
    }

    public function get_time(){
        return $this->time;
    }

    public function get_timestamp(){
        return $this->timestamp;
    }

    public function get_value(){
        return $this->value;
    }

    public function get_way(){
        return $this->way;
    }

    public function set_comment($comment){
        $this->comment = $comment;
    }

    public function set_company_id($id){
        $this->company_id = $id;
    }

    public function set_number_id($id){
        $this->number_id = $id;
    }

    public function set_meter_id($id){
        $this->meter_id = $id;
    }

    public function set_serial($serial){
        $this->serial = $serial;
    }

    public function set_time($time){
        $this->time = $time;
    }

    public function set_timestamp($time){
        $this->timestamp = $time;
    }

    public function set_value(array $values){
        $this->value = $values;
    }

    public function set_way($way){
        $this->way = $way;
    }
}