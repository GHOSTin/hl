<?php

class data_user_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->user = new data_user();
  }

  public function test_set_cellphone_1(){
    $this->user->set_cellphone('89222944742');
    $this->assertEquals('89222944742', $this->user->get_cellphone());
  }

  public function test_set_cellphone_2(){
    $this->setExpectedException('DomainException');
    $this->user->set_cellphone('8922294474201');
  }

  public function test_set_id_1(){
    $this->user->set_id(125);
    $this->assertEquals(125, $this->user->get_id());
  }

  public function test_set_id_2(){
    $this->setExpectedException('DomainException');
    $this->user->set_id(0);
  }

  public function test_set_id_3(){
    $this->setExpectedException('DomainException');
    $this->user->set_id(65536);
  }

  public function test_set_company_id_1(){
    $this->user->set_company_id(125);
    $this->assertEquals(125, $this->user->get_company_id());
  }

  public function test_set_company_id_2(){
    $this->setExpectedException('DomainException');
    $this->user->set_company_id(0);
  }

  public function test_set_company_id_3(){
    $this->setExpectedException('DomainException');
    $this->user->set_company_id(256);
  }

  public function test_set_firstname_1(){
    $this->user->set_firstname('Константин');
    $this->assertEquals('Константин', $this->user->get_firstname());
  }

  public function test_set_firstname_2(){
    $this->setExpectedException('DomainException');
    $this->user->set_firstname('');
  }

  public function test_set_firstname_3(){
    $this->setExpectedException('DomainException');
    $this->user->set_firstname('0123');
  }

  public function test_set_firstname_4(){
    $this->setExpectedException('DomainException');
    $this->user->set_firstname('Konstantin');
  }

  public function test_set_lastname_1(){
    $this->user->set_lastname('Константинопольский');
    $this->assertEquals('Константинопольский', $this->user->get_lastname());
  }

  public function test_set_lastname_2(){
    $this->setExpectedException('DomainException');
    $this->user->set_lastname('');
  }

  public function test_set_lastname_3(){
    $this->setExpectedException('DomainException');
    $this->user->set_lastname('0123');
  }

  public function test_set_lastname_4(){
    $this->setExpectedException('DomainException');
    $this->user->set_lastname('Konstantinopolskiy');
  }

  public function test_set_login_1(){
    $this->user->set_login('Nekrasov123');
    $this->assertEquals('Nekrasov123', $this->user->get_login());
  }

  public function test_set_login_2(){
    $this->user->set_login('Некрасов123');
    $this->assertEquals('Некрасов123', $this->user->get_login());
  }

  public function test_set_login_3(){
    $this->user->set_login('Некрасов123');
    $this->assertEquals('Некрасов123', $this->user->get_login());
  }

  public function test_set_login_4(){
    $this->setExpectedException('DomainException');
    $this->user->set_login('Не');
  }

  public function test_set_middlename_1(){
    $this->user->set_middlename('Константинович');
    $this->assertEquals('Константинович', $this->user->get_middlename());
  }

  public function test_set_middlename_2(){
    $this->user->set_middlename('');
    $this->assertEquals('', $this->user->get_middlename());
  }

  public function test_set_middlename_3(){
    $this->setExpectedException('DomainException');
    $this->user->set_middlename('0123');
  }

  public function test_set_middlename_4(){
    $this->setExpectedException('DomainException');
    $this->user->set_middlename('Konstantinovich');
  }

  public function test_set_status_1(){
    $this->user->set_status('true');
    $this->user->set_status('false');
    $this->assertEquals('false', $this->user->get_status());
  }

  public function test_set_status_2(){
    $this->setExpectedException('DomainException');
    $this->user->set_status('wrong');
  }

  public function test_set_telephone_1(){
    $this->user->set_telephone('89222944742');
    $this->assertEquals('89222944742', $this->user->get_telephone());
  }

  public function test_set_telephone_2(){
    $this->setExpectedException('DomainException');
    $this->user->set_telephone('8922294474201');
  }
}