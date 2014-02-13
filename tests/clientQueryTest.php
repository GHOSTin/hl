<?php

class clientQueryTest extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->query = new data_client_query();
  }

  public function testTime1(){
    $this->query->set_time(156);
    $this->assertEquals(156, $this->query->get_time());
  }

  public function testTime2(){
    try{
      $this->query->set_time();
    }catch(exception $e){
      return;
    }
    $this->fail('Пропустило установку времени без аргумента.');
  }

  public function testTime3(){
    try{
      $this->query->set_time(0);
    }catch(exception $e){
      return;
    }
    $this->fail('Пропустило установку времени нулем.');
  }

  public function testTime4(){
    try{
      $this->query->set_time(2145916801);
    }catch(exception $e){
      return;
    }
    $this->fail('Пропустило установку времени больше 2145916800.');
  }

  public function testTime5(){
    try{
      $this->query->set_time(-1);
    }catch(exception $e){
      return;
    }
    $this->fail('Пропустило установку времени отрицательным значением.');
  }

  public function testStatus1(){
    $this->query->set_status('new');
    $this->assertEquals('new', $this->query->get_status());
  }

  public function test_status_2(){
    try{
      $this->query->set_status('new');
    }catch(exception $e){
      $this->fail('Не прошел статус new.');
    }
  }

  public function test_status_3(){
    try{
      $this->query->set_status('accepted');
    }catch(exception $e){
      $this->fail('Не прошел статус accepted.');
    }
  }

  public function test_status_4(){
    try{
      $this->query->set_status('canceled');
    }catch(exception $e){
      $this->fail('Не прошел статус canceled.');
    }
  }

  public function test_status_5(){
    try{
      $this->query->set_status('opened');
    }catch(exception $e){
      return;
    }
    $this->fail('Прошел невалидный статус.');
  }

  public function test_reason_1(){
    $this->query->set_reason('Не указано описание');
    $this->assertEquals('Не указано описание', $this->query->get_reason());
  }

  public function test_query_id_1(){
    $this->query->set_query_id(123);
    $this->assertEquals(123, $this->query->get_query_id());
  }

  public function test_number_id_1(){
    $this->query->set_number_id(123);
    $this->assertEquals(123, $this->query->get_number_id());
  }

  public function test_company_id_1(){
    $this->query->set_company_id(123);
    $this->assertEquals(123, $this->query->get_company_id());
  }

  public function test_query_id_2(){
    try{
      $this->query->set_query_id();
    }catch(exception $e){
      return;
    }
    $this->fail('Прошел пустой аргумент.');
  }

  public function testText1(){
    $this->query->set_text('Прорвало трубу');
    $this->assertEquals('Прорвало трубу', $this->query->get_text());
  }

  public function testText2(){
    try{
      $this->query->set_text();
    }catch(exception $e){
      return;
    }
    $this->fail('Пропустило с пустым аргументом.');
  }

  public function testText3(){
    try{
      $this->query->set_text('Hello world!');
    }catch(exception $e){
      return;
    }
    $this->fail('Пропустило с английскими бувками.');
  }

  public function testText4(){
    try{
      $this->query->set_text('Буква');
    }catch(exception $e){
      return;
    }
    $this->fail('Пропустило меньше шести букв.');
  }
}