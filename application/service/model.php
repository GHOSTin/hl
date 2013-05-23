<?php
class model_service{

    /**
    * Возвращает список услуг
    * @return array из data_service
    */
    public static function get_services(data_company $company, data_service $service){
        model_company::verify_id($company);
        $sql = new sql();
        $sql->query("SELECT `id`, `company_id`, `name` FROM `services`
            WHERE `company_id` = :company_id");
        $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
        if(!empty($service->id)){
            self::verify_id($service);
            $sql->query(" AND `id` = :id");
            $sql->bind(':id', $service->id, PDO::PARAM_INT);
        }
        if(!empty($service->name)){
            self::verify_name($service);
            $sql->query(" AND `name` = :name");
            $sql->bind(':name', $service->name, PDO::PARAM_STR);
        }
        return $sql->map(new data_service(), 'Проблема при выборке услуг.');
    }

    /**
    * Создает новую услугу
    * @return data_service
    */
    public static function create_service(data_company $company, data_service $service){
        self::verify_name($service);
        model_company::verify_id($company);
        if(count(self::get_services($company, $service)) > 0)
            throw new e_model('Такая услуга уже существует.');
        $service->id = self::get_insert_id($company);
        $service->company_id = $company->id;
        self::verify_id($service);
        self::verify_company_id($service);
        self::verify_name($service);
        $sql = new sql();
        $sql->query("INSERT INTO `services` (`id`, `company_id`, `name`)
                    VALUES (:id, :company_id, :name)");
        $sql->bind(':id', $service->id, PDO::PARAM_INT);
        $sql->bind(':company_id', $service->company_id, PDO::PARAM_INT);
        $sql->bind(':name', $service->name, PDO::PARAM_STR);
        $sql->execute('Проблемы при создании услуги.');
        $sql->close();
        return $service;
    }

    /**
    * Возвращает следующий для вставки идентификатор услуги.
    * @return int
    */
    private static function get_insert_id(data_company $company){
        model_company::verify_id($company);
        $sql = new sql();
        $sql->query("SELECT MAX(`id`) as `max_id` FROM `services`
                    WHERE `company_id` = :company_id");
        $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
        $sql->execute('Проблема при опредении следующего service_id.');
        if($sql->count() !== 1)
            throw new e_model('Проблема при опредении следующего service_id.');
        $id = (int) $sql->row()['max_id'] + 1;
        $sql->close();
        return $id;
    }

    /**
    * Создает новую услугу
    * @return data_service
    */
    public static function rename_service(data_company $company, data_service $service){
        self::verify_id($service);
        self::verify_name($service);
        $service_params = new data_service();
        $service_params->name = $service->name;
        if(count(self::get_services($company, $service_params)) > 0)
            throw new e_model('Услуга с таким именем уже существует.');
        $service_params = new data_service();
        $service_params->id = $service->id;
        $services = self::get_services($company, $service_params);
        if(count($services) !== 1)
            throw new e_model('Услуга с таким идентификатором не существует.');
        $new_service = $services[0];
        self::is_data_service($new_service);
        $new_service->name = $service->name;
        $sql = new sql();
        $sql->query("UPDATE `services` SET `name` = :name
                    WHERE `company_id` = :company_id AND `id` = :id");
        $sql->bind(':id', $new_service->id, PDO::PARAM_INT);
        $sql->bind(':company_id', $new_service->company_id, PDO::PARAM_INT);
        $sql->bind(':name', $new_service->name, PDO::PARAM_STR);
        $sql->execute('Проблема при переименовании услуги.');
        $sql->close();
        return $new_service;
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