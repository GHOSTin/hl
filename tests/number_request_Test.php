<?php
use domain\number_request;
use domain\number;
use domain\query;

class doamin_number_request_Test extends PHPUnit_Framework_TestCase{

  public function test_message(){
    $time = time();
    $query = new query();
    $number = new number();
    $request = new number_request($number, 'Описание запроса');
    $request->set_query($query);
    $this->assertEquals('Описание запроса', $request->get_message());
    $this->assertGreaterThanOrEqual($time, $request->get_time());
    $this->assertEquals($number, $request->get_number());
    $this->assertEquals($query, $request->get_query());
  }
}