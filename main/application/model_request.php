<?php
class model_request{

  private $get = [];
  private $post = [];

  public function __construct(){
    $this->get = $_GET;
    $this->post = $_POST;
  }

  public function set_property($key, $value){
    $this->get[$key] = $value;
  }

  public function take_get($key){
    if(isset($this->get[$key]))
      return $this->get[$key];
    else
      return null;
  }

  public function GET($key){
    return $this->take_get($key);
  }

  public function POST($key){
    return $this->take_post($key);
  }

  public function FILES($key){
    return $_FILES[$key];
  }

  public function take_post($key){
    if(isset($this->post[$key]))
      return $this->post[$key];
    else
      return null;
  }
}