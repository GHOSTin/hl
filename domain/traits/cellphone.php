<?php namespace domain\traits;

use DomainException;

trait cellphone{

  public function prepare_cellphone($text){
    preg_match_all('/[0-9]/', $text, $matches);
    $cellphone = implode('', $matches[0]);
    if(preg_match('|^9[0-9]{9}$|', $cellphone))
      return $cellphone;
    if(preg_match('|^7(9[0-9]{9})$|', $cellphone, $m))
      return  $m[1];
    if(preg_match('|^8(9[0-9]{9})$|', $cellphone, $m))
      return $m[1];
    return null;
  }
}