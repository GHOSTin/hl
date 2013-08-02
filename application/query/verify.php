<?php
class verify_query{



    /**
    * Верификация идентификатора заявки.
    */
    public static function id(data_query $query){
        if($query->id < 1)
            throw new e_model('Идентификатор заявки задан не верно.');
    }

    /**
    * Верификация статуса заявки.
    */
    public static function status(data_query $query){
        if(!in_array($query->status, ['open', 'close', 'working', 'reopen']))
            throw new e_model('Статус заявки задан не верно.');
    }

    /**
    * Верификация инициатора заявки.
    */
    public static function initiator(data_query $query){
        if(!in_array($query->initiator, ['number', 'house']))
            throw new e_model('Инициатор заявки задан не верно.');
    }

    /**
    * Верификация статуса оплаты заявки.
    */
    public static function payment_status(data_query $query){
        if(!in_array($query->payment_status, ['paid', 'unpaid', 'recalculation']))
            throw new e_model('Статус оплаты заявки задан не верно.');
    }

    /**
    * Верификация ворнинга заявки.
    */
    public static function warning_status(data_query $query){
        if(!in_array($query->warning_status, ['hight', 'normal', 'planned']))
            throw new e_model('Статус ворнинга заявки задан не верно.');
    }

    /**
    * Верификация идентификатора участка.
    */
    public static function department_id(data_query $query){
        $department = new data_department();
        $department->id = $query->department_id;
        $department->verify('id');
    }

    /**
    * Верификация идентификатора дома.
    */
    public static function house_id(data_query $query){
        $house = new data_house();
        $house->id = $query->house_id;
        $house->verify('id');
    }

    /**
    * Верификация идентификатора причины закрытия.
    */
    public static function close_reason_id(data_query $query){
        if($query->close_reason_id < 1)
            throw new e_model('Идентификатор причины закрытия задан не верно.');
    }

    /**
    * Верификация идентификатора причины закрытия.
    */
    public static function work_type_id(data_query $query){
        $work_type = new data_query_work_type();
        $work_type->id = $query->worktype_id;
        $work_type->verify('id');
    }

    /**
    * Верификация времени открытия заявки.
    */
    public static function time_open(data_query $query){
        if($query->time_open < 0)
            throw new e_model('Время открытия заявки задано не верно.');
    }

    /**
    * Верификация времени передачи в работу заявки.
    */
    public static function time_work(data_query $query){
        if($query->time_work < 0)
            throw new e_model('Время передачи в работу заявки задано не верно.');
    }

    /**
    * Верификация времени закрытия заявки.
    */
    public static function time_close(data_query $query){
        if($query->time_close < 0)
            throw new e_model('Время закрытия заявки задано не верно.');
    }

    /**
    * Верификация ФИО контакта заявки.
    */
    public static function verify_contact_fio(data_query $query){
    }

    /**
    * Верификация телефона контакта заявки.
    */
    public static function verify_contact_telephone(data_query $query){
    }

    /**
    * Верификация сотового телефона контакта заявки.
    */
    public static function verify_contact_cellphone(data_query $query){
    }

    /**
    * Верификация описания заявки.
    */
    public static function verify_description(data_query $query){
        if(empty($query->description))
            throw new e_model('Описание заявки заданы не верно.');
    }

    /**
    * Верификация причины закрытия заявки.
    */
    public static function verify_close_reason(data_query $query){
        if(empty($query->close_reason))
            throw new e_model('Описание закрытия заявки заданы не верно.');
    }

    /**
    * Верификация номера заявки.
    */
    public static function verify_number(data_query $query){
        if($query->number < 0)
            throw new e_model('Номер заявки задан не верно.');
    }

    /**
    * Верификация инспеции заявки.
    */
    public static function verify_inspection(data_query $query){
        if(empty($query->inspection))
            throw new e_model('Инспекция заявки задана не верно.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_query $query){
        $company = new data_company();
        $company->id = $query->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора улицы.
    */
    public static function street_id(data_query $query){
        $street = new data_street();
        $street->id = $query->street_id;
        $street->verify('id');
    }
}