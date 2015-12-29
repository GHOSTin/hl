<?php namespace tests\domain;

use domain\outage;
use domain\workgroup;
use domain\house;
use domain\user;
use PHPUnit_Framework_TestCase;

class outage_Test extends PHPUnit_Framework_TestCase{

  public function test_instance(){
    $workgroup = new workgroup();
    $user = new user();
    $house = new house();
    $houses = [$house];
    $users = [$user];
    $outage = outage::new_instance(1396332000, 1396332001, $workgroup, $user, $houses, $users, 'Привет');
    $this->assertSame($workgroup, $outage->get_category());
    $this->assertSame($user, $outage->get_user());
    $this->assertGreaterThanOrEqual(time(), $outage->get_id());
    $this->assertEquals(1396332000, $outage->get_begin());
    $this->assertEquals(1396332001, $outage->get_target());
    $this->assertEquals('Привет', $outage->get_description());
    $this->assertEquals($houses, $outage->get_houses()->toArray());
    $this->assertEquals($users, $outage->get_performers()->toArray());
  }
}