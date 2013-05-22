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
            self::verify_id($service);
            $sql->query(" WHERE `id` = :id");
            $sql->bind(':id', $service->id, PDO::PARAM_INT);
        }
        if(!empty($service->name)){
            self::verify_name($service);
            $sql->query(" WHERE `name` = :name");
            $sql->bind(':name', $service->name, PDO::PARAM_INT);
        }
        return $sql->map(new data_service(), 'Проблема при выборке услуг.');
    }

    /*
    * Создает новую услугу
    */
    public static function create_service(data_company $company, data_service $service){
        self::verify_name($service);
        model_company::verify_id($company);
        if(count(self::get_services($company, $service)) > 0)
            throw new e_model('Такая услуга уже существует.');
        $service->id = self::get_insert_id();
        $service->company_id = $company->id;
        $sql = new sql();
        $sql->query("INSERT INTO `services` (`id`, `company_id`, `name`)
                    VALUES (:id, :company_id, :name)");
        self::verify_id($service);
        self::verify_company_id($service);
        self::verify_name($service);
        $sql->bind(':id', $service->id, PDO::PARAM_INT);
        $sql->bind(':company_id', $service->company_id, PDO::PARAM_STR);
        $sql->bind(':name', $service->name, PDO::PARAM_INT);
        $sql->execute('Проблемы при создании услуги.');
        $sql->close();
        return $service;
    }

    /**
    * Возвращает следующий для вставки идентификатор услуги.
    * @return int
    */
    private static function get_insert_id(){
        $sql = new sql();
        $sql->query("SELECT MAX(`id`) as `max_id` FROM `services`");
        $sql->execute('Проблема при опредении следующего service_id.');
        if($sql->count() !== 1)
            throw new e_model('Проблема при опредении следующего service_id.');
        $id = (int) $sql->row()['max_id'] + 1;
        $sql->close();
        return $id;
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
        if(!preg_match('/^[а-яА-Я1-9 ]+$/u', $service->name))
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