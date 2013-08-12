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
}