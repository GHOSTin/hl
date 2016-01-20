<?php namespace tests\domain;

use domain\user;
use PHPUnit_Framework_TestCase;

class user_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->user = new user();
  }

  public function test_check_access_1(){
    $this->setExpectedException('DomainException');
    $this->user->check_access('wrong/access');
  }

  public function test_check_access_2(){
    $this->user->update_access('queries/general_access');
    $this->assertTrue($this->user->check_access('queries/general_access'));
    $this->user->update_access('queries/general_access');
    $this->assertFalse($this->user->check_access('queries/general_access'));
  }

  public function test_set_cellphone_1(){
    $this->user->set_cellphone('9222944742');
    $this->assertEquals('9222944742', $this->user->get_cellphone());
    $this->user->set_cellphone('89222944742');
    $this->assertEquals('9222944742', $this->user->get_cellphone());
    $this->user->set_cellphone('+79222944742');
    $this->assertEquals('9222944742', $this->user->get_cellphone());
    $this->user->set_cellphone('');
    $this->assertEquals(null, $this->user->get_cellphone());
    $this->user->set_cellphone('+7922294474201');
    $this->assertEquals(null, $this->user->get_cellphone());
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

  public function test_set_hash(){
    $this->user->set_hash('Некрасов123');
    $this->assertEquals('Некрасов123', $this->user->get_hash());
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

  public function test_unblock(){
    $this->user->unblock();
    $this->assertEquals('true', $this->user->get_status());
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

  public function test_generate_hash(){
    $this->assertEquals('d514dee5e76bbb718084294c835f312c',
                        user::generate_hash('password', 'salt'));
  }

  public function test_JsonSerialize(){
    $array = ['id' => 123, 'firstname' => 'Евгений', 'lastname' => 'Некрасов',
              'middlename' => 'Валерьевич'];
    $this->user->set_id($array['id']);
    $this->user->set_firstname($array['firstname']);
    $this->user->set_lastname($array['lastname']);
    $this->user->set_middlename($array['middlename']);
    $this->assertEquals($array, $this->user->JsonSerialize());
  }

  public function test_update_restriction_1(){
    $this->setExpectedException('DomainException');
    $this->user->update_restriction('wrong', '3');
  }

  public function test_update_restriction_2(){
    $this->user->update_restriction('departments', '3');
    $this->user->update_restriction('departments', '2');
    $this->assertEquals(['3', '2'], $this->user->get_restriction('departments'));
    $this->user->update_restriction('departments', '3');
    $this->assertEquals(['2'], $this->user->get_restriction('departments'));
  }

  public function test_update_restriction_3(){
    $this->user->update_restriction('categories', '3');
    $this->user->update_restriction('categories', '2');
    $this->assertEquals(['3', '2'], $this->user->get_restriction('categories'));
    $this->user->update_restriction('categories', '3');
    $this->assertEquals(['2'], $this->user->get_restriction('categories'));
  }
}