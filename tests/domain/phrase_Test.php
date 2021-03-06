<?php namespace tests\domain;

use domain\phrase;
use domain\workgroup;
use PHPUnit_Framework_TestCase;

class phrase_Test extends PHPUnit_Framework_TestCase{

  public function test_instance(){
    $workgroup = new workgroup();
    $phrase = phrase::new_instance($workgroup, 'Привет');
    $this->assertSame($workgroup, $phrase->get_workgroup());
    $this->assertEquals('Привет', $phrase->get_text());
    $this->assertGreaterThanOrEqual(time(), $phrase->get_id());
    $this->assertEquals(json_encode(['text' => 'Привет']), json_encode($phrase));
  }
}