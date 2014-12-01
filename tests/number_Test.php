<?php

use \domain\number;
use \domain\flat;

class number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->number = new number();
  }

  public function test_set_id_1(){
    $this->number->set_id(125);
    $this->assertEquals(125, $this->number->get_id());
  }

  public function test_set_id_2(){
    $this->setExpectedException('DomainException');
    $this->number->set_id(0);
  }

  public function test_set_id_3(){
    $this->setExpectedException('DomainException');
    $this->number->set_id(16777216);
  }

  public function test_set_id_4(){
    $this->setExpectedException('DomainException');
    $this->number->set_id(-125);
  }

  public function test_set_cellphone_1(){
    $this->number->set_cellphone('9222944742');
    $this->assertEquals('9222944742', $this->number->get_cellphone());
    $this->number->set_cellphone('');
    $this->assertEquals('', $this->number->get_cellphone());
  }

  public function test_set_cellphone_2(){
    $this->setExpectedException('DomainException');
    $this->number->set_cellphone('+79222944742');
  }

  public function test_set_cellphone_3(){
    $this->setExpectedException('DomainException');
    $this->number->set_cellphone('222944742');
  }

  public function test_set_telephone_1(){
    $this->number->set_telephone('647957');
    $this->assertEquals('647957', $this->number->get_telephone());
    $this->number->set_telephone('02');
    $this->assertEquals('02', $this->number->get_telephone());
    $this->number->set_telephone('83439647957');
    $this->assertEquals('83439647957', $this->number->get_telephone());
    $this->number->set_telephone('');
    $this->assertEquals('', $this->number->get_telephone());
  }

  public function test_set_telephone_2(){
    $this->setExpectedException('DomainException');
    $this->number->set_telephone('883439647957');
  }

  public function test_generate_hash(){
    $this->assertEquals('d514dee5e76bbb718084294c835f312c',
                        number::generate_hash('password', 'salt'));
  }

  public function test_set_flat(){
    $flat = new flat();
    $this->number->set_flat($flat);
    $this->assertSame($flat, $this->number->get_flat());
  }

  public function test_set_hash(){
    $this->number->set_hash('aa98a96aa33a73df71d3f045cd07b680');
    $this->assertEquals('aa98a96aa33a73df71d3f045cd07b680',
      $this->number->get_hash());
  }

  public function test_set_status_1(){
    $this->number->set_status('true');
    $this->assertEquals('true', $this->number->get_status());
    $this->number->set_status('false');
    $this->assertEquals('false', $this->number->get_status());
  }

  public function test_set_status_2(){
    $this->setExpectedException('DomainException');
    $this->number->set_status('truefalse');
  }

  public function test_set_number_1(){
    $this->number->set_number('0385744');
    $this->assertEquals('0385744', $this->number->get_number());
    $this->number->set_number('12345678901234567890');
    $this->assertEquals('12345678901234567890', $this->number->get_number());
    $this->number->set_number('');
    $this->assertEquals('', $this->number->get_number());
  }

  public function test_set_number_2(){
    $this->setExpectedException('DomainException');
    $this->number->set_number('123456789012345678901');
  }

  public function test_set_fio(){
    $this->number->set_fio('Некрасов Евгений');
    $this->assertEquals('Некрасов Евгений', $this->number->get_fio());
  }

  public function test_get_queries(){
    $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection',
                            $this->number->get_queries());
  }

  public function test_get_accruals(){
    $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection',
                            $this->number->get_accruals());
  }

  public function test_set_email_1(){
    $this->number->set_email('nekrasov@mlsco.ru');
    $this->assertEquals('nekrasov@mlsco.ru', $this->number->get_email());
    $this->number->set_email('nekrasov-1@mlsco.ru');
    $this->assertEquals('nekrasov-1@mlsco.ru', $this->number->get_email());
  }
}