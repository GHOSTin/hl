<?php
class verify_meter{

    /**
    * Верификация идентификатора счетчика.
    */
    public static function company_id(data_meter $meter){
        $company = new data_company();
        $company->set_id($meter->get_company_id());
        $company->verify('id');
    }

    /**
    * Верификация времени поверки счетчика.
    */
    public static function chektime(data_meter $meter){
        if($meter->get_chektime() < 0)
            throw new e_model('Время поверки счетчика задано не верно.');
    }

    /**
    * Верификация идентификатора счетчика.
    */
    public static function id(data_meter $meter){
        if(!preg_match('/^[0-9]{1,8}$/', $meter->get_id()))
            throw new e_model('Идентификатор счетчика задан не верно.');
        if($meter->get_id() > 16777215 OR $meter->get_id() < 1)
            throw new e_model('Идентификатор счетчика задан не верно.');
    }

    /**
    * Верификация названия счетчика.
    */
    public static function name(data_meter $meter){
        if(!preg_match('/^[а-яА-Яa-zA-Z0-9 -]{1,20}$/u', $meter->get_name()))
            throw new e_model('Название счетчика задано не верно.');
    }

    /**
    * Верификация разрядности счетчика.
    */
    public static function capacity(data_meter $meter){
        if($meter->get_capacity() < 1 OR $meter->get_capacity() > 9)
            throw new e_model('Разрядность задана не верно.');
    }

    /**
    * Верификация тарифности счетчика.
    */
    public static function rates(data_meter $meter){
        if($meter->get_rates() < 1 OR $meter->get_rates() > 3)
            throw new e_model('Тарифность задана не верно.');
    }

    /**
    * Верификация периодов счетчика.
    */
    public static function periods(data_meter $meter){
        if(!empty($meter->periods))
            foreach($meter->periods as $period)
                $period = (int) ($period);
                if($period < 0 OR $period > 240)
                    throw new e_model('Период задан не верно.');
    }

    /**
    * Верификация услуги счетчика.
    */
    public static function service(data_meter $meter){
        $services = ['cold_water', 'hot_water', 'electrical'];
        foreach($meter->service as $service)
            if(!in_array($service, $services))
                throw new e_model('Услуга задана не верно.');
    }
}