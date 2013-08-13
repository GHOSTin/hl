<?php
class mapper_meter{

    private $company;

    public function __construct(data_company $company){
        $this->company = $company;
    }

    public function create(data_meter $meter){
        $meter->set_id($this->get_insert_id());
        $meter->set_company_id($this->company->id);
        $meter->verify('id', 'company_id', 'name', 'rates', 'capacity');
        $sql = new sql();
        $sql->query("INSERT INTO `meters` (`id`, `company_id`, `name`, `rates`, `capacity`,`periods`)
                    VALUES (:id, :company_id, :name, :rates, :capacity, :periods)");
        $sql->bind(':id', $meter->get_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', $meter->get_company_id(), PDO::PARAM_INT);
        $sql->bind(':name', $meter->get_name(), PDO::PARAM_STR);
        $sql->bind(':rates', $meter->get_rates(), PDO::PARAM_INT);
        $sql->bind(':capacity', $meter->get_capacity(), PDO::PARAM_INT);
        $sql->bind(':periods', implode(';', $meter->get_periods()), PDO::PARAM_STR);
        $sql->execute('Проблемы при создании счетчика.');
        $sql->close();
        return $meter;
    }

    public function find($id){
        $this->company->verify('id');
        $meter = new data_meter();
        $meter->set_id($id);
        $meter->verify('id');
        $sql = new sql();
        $sql->query("SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
                    FROM `meters` WHERE `company_id` = :company_id AND `id` = :id");
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':id', $meter->id, PDO::PARAM_INT);
        $meters = $sql->map(new data_meter(), 'Проблема при запросе счетчика.');
        $count = count($meters);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых счетчиков.');
        return  $meters[0];
    }

    public function find_by_name($name){
        $this->company->verify('id');
        $meter = new data_meter();
        $meter->set_name($name);
        $meter->verify('name');
        $sql = new sql();
        $sql->query("SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
                    FROM `meters` WHERE `company_id` = :company_id AND `name` = :name");
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':name', $meter->name, PDO::PARAM_STR);
        $meters = $sql->map(new data_meter(), 'Проблема при запросе счетчика.');
        $count = count($meters);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых счетчиков.');
        return  $meters[0];
    }

    /**
    * Возвращает следующий для вставки идентификатор счетчика.
    * @return int
    */
    private function get_insert_id(){
        $this->company->verify('id');
        $sql = new sql();
        $sql->query("SELECT MAX(`id`) as `max_id` FROM `meters`
                    WHERE `company_id` = :company_id");
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->execute('Проблема при опредении следующего meter_id.');
        if($sql->count() !== 1)
            throw new e_model('Проблема при опредении следующего meter_id.');
        $id = (int) $sql->row()['max_id'] + 1;
        $sql->close();
        return $id;
    }

    public function update(data_meter $meter){
        $this->company->verify('id');
        $meter->verify('id', 'name', 'capacity', 'rates', 'periods');
        $sql = new sql();
        $sql->query('UPDATE `meters` SET `name` = :name, `capacity` = :capacity,
                    `rates` = :rates, `periods` = :periods, `service` = :services
                    WHERE `company_id` = :company_id AND `id` = :id');
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':id', $meter->get_id(), PDO::PARAM_INT);
        $sql->bind(':name', $meter->get_name(), PDO::PARAM_STR);
        $sql->bind(':capacity', $meter->get_capacity(), PDO::PARAM_INT);
        $sql->bind(':rates', $meter->get_rates(), PDO::PARAM_INT);
        $sql->bind(':periods', implode(';', $meter->get_periods()), PDO::PARAM_STR);
        $sql->bind(':services', implode(',', $meter->get_services()), PDO::PARAM_STR);
        $sql->execute('Проблема при обновлении счетчика.');
        return $meter;
    }
}