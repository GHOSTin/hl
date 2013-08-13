<?php
/*
* Связь с таблицей `meters`.
*/
final class data_meter extends data_object{
    
    public $capacity;
    public $company_id;
    public $id;
    public $name;
    public $periods;
    public $rates;
    public $service;

    public function __construct(){
        if(empty($this->service))
            $this->service = [];
        else
            $this->service = explode(',', $this->service);

        $this->periods = (empty($this->periods))? []: explode(';', $this->periods);
    }

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_meter::$value($this);
    }

    public function add_period($period){
        if(in_array($period, $this->periods))
            throw new e_model('Такой период уже задан в счетчике.');
        $this->periods[] = (int) $period;
    }

    public function remove_period($period){
        $rs = array_search($period, $this->periods);
        if($rs === false)
            throw new e_model('Периода не было в этом счетчике.');
        unset($this->periods[$rs]);
    }

    public function get_id(){
        return $this->id;
    }

    public function get_company_id(){
        return $this->company_id;
    }

    public function get_name(){
        return $this->name;
    }

    public function get_capacity(){
        return $this->capacity;
    }

    public function get_rates(){
        return $this->rates;
    }

    public function set_id($id){
        $this->id = $id;
    }

    public function set_company_id($id){
        $this->company_id = $id;
    }

    public function set_name($name){
        $this->name = $name;
    }

    public function set_capacity($capacity){
        $this->capacity = $capacity;
    }

    public function set_rates($rates){
        $this->rates = $rates;
    }

    public function get_periods(){
        return $this->periods;
    }
}