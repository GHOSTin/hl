<?php

class factory_company_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->factory = new factory_company();
  }

  public function test_1(){
    $company = $this->factory->build(['id' => 1, 'name' => 'Наш город']);
    $this->assertEquals(1, $company->get_id());
    $this->assertEquals('Наш город', $company->get_name());
  }
}