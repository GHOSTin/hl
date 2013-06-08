<?php
/*
* Связь с таблицей `meters`.
*/
final class data_meter extends data_object{
    
    public $capacity;
    // public $checktime;
    public $company_id;
    public $id;
    public $name;
    // public $serial;
    public $periods;
    public $rates;
    public $service;
    // public $period;
    // public $date_release;
    // public $date_install;
    // public $date_checking;
    // public $place;

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
}