<?php

use domain\phrase;
use domain\workgroup;

class phrase_Test extends PHPUnit_Framework_TestCase{

  public function test_instance(){
    $workgroup = new workgroup();
    $phrase = phrase::new_instance($workgroup, 'Привет');
    $this->assertSame($workgroup, $phrase->get_workgroup());
    $this->assertEquals('Привет', $phrase->get_text());
    $this->assertGreaterThanOrEqual(time(), $phrase->get_id());
  }
}