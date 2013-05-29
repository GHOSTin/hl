<?php
/*
* Связь с таблицей `meters`.
*/
final class data_meter extends data_object{
    
    public $capacity;
    public $checktime;
    public $company_id;
    public $id;
    public $name;
    public $rates;
    public $serial;
    public $service;

    public function __construct(){
        if(empty($this->service))
            $this->service = [];
        else
            $this->service = explode(',', $this->service);
    }   
}