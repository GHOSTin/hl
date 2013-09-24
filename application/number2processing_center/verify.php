<?php
class verify_number2processing_center{

  /**
  * Верификация идентификатора лицевого счета.
  */
  public static function identifier(data_number2processing_center $n2c){
      if(!preg_match('/^[а-яА-Яa-zA-Z0-9]{0,20}$/u', $n2c->get_identifier()))
          throw new e_model('Идентификатор лицевого счета задан не верно.');
  }
}