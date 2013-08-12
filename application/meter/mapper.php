<?php
class mapper_meter{

    private $company;

    public function __construct(data_company $company){
        $this->company = $company;
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

    public function update(data_meter $meter){
        $this->company->verify('id');
        $meter->verify('id', 'name', 'capacity', 'rates');
        $sql = new sql();
        $sql->query('UPDATE `meters` SET `name` = :name, `capacity` = :capacity,
                    `rates` = :rates
                    WHERE `company_id` = :company_id AND `id` = :id');
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':id', $meter->get_id(), PDO::PARAM_INT);
        $sql->bind(':name', $meter->get_name(), PDO::PARAM_STR);
        $sql->bind(':capacity', $meter->get_capacity(), PDO::PARAM_INT);
        $sql->bind(':rates', $meter->get_rates(), PDO::PARAM_INT);
        $sql->execute('Проблема при обновлении счетчика.');
        return $meter;
    }
}