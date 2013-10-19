<?php
class model_request{

  public function take_get($key){
    if(isset($_GET[$key]))
      return $_GET[$key];
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
    if(isset($_POST[$key]))
      return $_POST[$key];
    else
      return null;
  }
}