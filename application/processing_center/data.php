<?php
/**
* Связь с таблицей `processing_centers`
*/
class data_processing_center extends data_object{

    private $id;
    private $name;

    public function get_id(){
      return $this->id;
    }

    public function get_name(){
      return $this->name;
    }

    public function set_id($id){
      $this->id = (int) $id;
    }

    public function set_name($name){
      $this->name = (string) $name;
    }
    
    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_processing_center::$value($this);
    }
}