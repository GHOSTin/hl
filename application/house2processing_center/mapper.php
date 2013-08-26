<?php
class mapper_house2processing_center{

    private $company;
    private $house;

    public function __construct(data_company $company, data_house $house){
        $this->company = $company;
        $this->house = $house;
        $this->company->verify('id');
        $this->house->verify('id');
    }
}