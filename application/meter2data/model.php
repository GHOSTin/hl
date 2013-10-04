<?php
class model_meter2data{

  private $company;
  private $n2m;

  
  public function __construct(data_company $company, data_number2meter $n2m){
      $this->company = $company;
      $this->company->verify('id');
      $this->n2m = $n2m;
      $this->n2m->get_number()->verify('id');
      $this->n2m->get_meter()->verify('id');
      $this->n2m->verify('serial');
  }

  public function get_values($begin, $end){
    return (new mapper_meter2data($this->company, $this->n2m))
      ->get_values($begin, $end);
  }

  public function get_value($time){
    return (new mapper_meter2data($this->company, $this->n2m))->find($time);
  }

  public function init_values($begin, $end){
    $values = $this->get_values($begin, $end);
    if(!empty($values))
      foreach($values as $value)
        $this->n2m->add_value($value);
  }

  public function update_value($time, array $values, $way, $comment, $timestamp){
    $mapper = new mapper_meter2data($this->company, $this->n2m);
    $value = $mapper->find($time);
    if(!is_null($value)){
      $value->set_way($way);
      $value->set_comment($comment);
      $value->set_value($values);
      $value->set_timestamp($timestamp);
      $mapper->update($value);
    }else{
      $value = new data_meter2data();
      $value->set_way($way);
      $value->set_comment($comment);
      $value->set_value($values);
      $value->set_time($time);
      $value->set_timestamp(time());
      $mapper->insert($value);
    }
  }
}
