<?php
class model_request{

  public function take_get($key){
    if(isset($_GET[$key]))
      return $_GET[$key];
    else
      return null;
  }

  public function take_post($key){
    if(isset($_POST[$key]))
      return $_POST[$key];
    else
      return null;
  }
}