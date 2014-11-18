<?php
/**
 * @property data_task2comment t2c
 * @property data_user user
 */
class data_task2comment_Test extends PHPUnit_Framework_TestCase {
  protected function setUp() {
    $this->t2c = new data_task2comment();
    $this->user = new data_user();
  }

  public function test_set_message_1(){
    $this->t2c->set_message('Test1');
    $this->assertEquals('Test1', $this->t2c->get_message());
  }

  public function test_set_time_1(){
    $this->t2c->set_time(1397562800);
    $this->assertEquals(1397562800, $this->t2c->get_time());
  }

  public function test_set_user_1(){
    $this->t2c->set_user($this->user);
    $this->assertInstanceOf('data_user', $this->t2c->get_user());
    $this->assertEquals($this->user, $this->t2c->get_user());
  }
}