<?php
class data_number2processing_center{

  private $identifier;
  private $processing_center;

  public function __call($method, $args){
    return $this->processing_center->$method($args);
  }

  public function __construct(data_processing_center $center){
    $this->processing_center = $center;
    data_processing_center::verify_id($this->processing_center->get_id());
  }

  public function get_identifier(){
    return $this->identifier;
  }

  public function set_identifier($id){
    self::verify_identifier($id);
    $this->identifier = (string) $id;
  }

  public static function verify_identifier($ident){
    if(!preg_match('/^[а-яА-Яa-zA-Z0-9]{0,20}$/u', $ident))
        throw new e_model('Идентификатор лицевого счета задан не верно.');
  }
}