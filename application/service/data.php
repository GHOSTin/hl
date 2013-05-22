<?php
/*
* Связь с таблицей `services`.
*/
class data_service extends data_object{

    /**
    * Идентификатор компании
    */
    public $company_id;

    /**
    * Идентификатор услуги
    */
    public $id;
    
    /**
    * Название услуги
    */
    public $name;
}