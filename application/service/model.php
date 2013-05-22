<?php
class model_service{

    /**
    * Возвращает список услуг
    * @return array из data_service
    */
    public static function get_services(data_company $company, data_service $service){
        $sql = new sql();
        $sql->query("SELECT `id`, `company_id`, `name` FROM `services`");
        if(!empty($service->id)){
            $sql->query(" WHERE `id` = :id");
            $sql->bind(':id', $service->id, PDO::PARAM_INT);
        }
        return $sql->map(new data_service(), 'Проблема при выборке услуг.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function verify_company_id(data_service $service){
        if($service->company_id < 1)
            throw new e_model('Идентификатор компании задан не верно.');
    }

    /**
    * Верификация идентификатора услуги.
    */
    public static function verify_id(data_service $service){
        if($service->id < 1)
            throw new e_model('Идентификатор услуги задан не верно.');
    }

    /**
    * Верификация имени услуги.
    */
    public static function verify_name(data_service $service){
        if(empty($service->name))
            throw new e_model('Название услуги задано не верно.');
    }

    /**
    * Верификация типа объекта пользователя.
    */
    public static function is_data_service($service){
        if(!($service instanceof data_service))
            throw new e_model('Возвращен объект не является услугой.');
    }
}