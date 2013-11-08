<?php
class model_meter2data{

  private $company;
  private $number;
  private $meter;
  
  public function __construct(data_company $company, data_number $number,
    data_number2meter $meter){
    $this->company = $company;
    $this->number = $number;
    $this->meter = $meter;
    data_company::verify_id($this->company->get_id());
    data_number::verify_id($this->number->get_id());
    data_meter::verify_id($this->meter->get_id());
    data_meter::verify_rates($this->meter->get_rates());
  }

  public function get_values($begin, $end){
    return (new mapper_meter2data($this->company, $this->number, $this->meter))
      ->get_values($begin, $end);
  }

  public function get_value($time){
    return (new mapper_meter2data($this->company, $this->number, $this->meter))
      ->find($time);
  }

  public function init_values($begin, $end){
    $values = $this->get_values($begin, $end);
    if(!empty($values))
      foreach($values as $value)
        $this->meter->add_value($value);
  }

  public function update_value($time, array $values, $way, $comment, $timestamp){
    $mapper = new mapper_meter2data($this->company, $this->number, $this->meter);
    $value = $mapper->find($time);
    if(!is_null($value)){
      $value->set_way($way);
      $value->set_comment($comment);
      if(!empty($values)){
        $limit = $this->meter->get_rates();
        for($i = 0; $i < $limit; $i++)
          $value->set_value($i, $values[$i]);
      }
      $value->set_timestamp($timestamp);
      $mapper->update($value);
    }else{
      $value = new data_meter2data();
      $value->set_way($way);
      $value->set_comment($comment);
      if(!empty($values)){
        $limit = $this->meter->get_rates();
        for($i = 0; $i < $limit; $i++)
          $value->set_value($i, $values[$i]);
      }
      $value->set_time($time);
      $value->set_timestamp($timestamp);
      $mapper->insert($value);
    }
  }
}
