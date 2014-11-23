<?php

class controller_api {

  public static function private_get_chat_options(model_request $request){
    return ['user' => $_SESSION['user'], 'host' => application_configuration::chat_host,
        'port' => application_configuration::chat_port];
  }

}