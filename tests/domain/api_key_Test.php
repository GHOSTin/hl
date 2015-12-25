<?php namespace tests\domain;

use PHPUnit_Framework_TestCase;
use domain\api_key;

class api_key_Test extends PHPUnit_Framework_TestCase {

  public function test_1(){
    $key = api_key::new_instance(' Даниловское ');
    $this->assertEquals('Даниловское', $key->get_name());
    $this->assertStringMatchesFormat('%s', $key->get_hash());
  }
}