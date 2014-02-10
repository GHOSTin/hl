<?php

class controller_about{

  public static function private_show_default_page(model_request $request){
    return ['version' => file_get_contents(ROOT.'/version')];
  }
}