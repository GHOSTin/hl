<?php

use \domain\group;
use \domain\user;

class group_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->group = new group();
  }

  public function test_set_name_1(){
    $this->group->set_name('Диспетчера');
    $this->assertEquals('Диспетчера', $this->group->get_name());
    $this->group->set_name('Группа 1-2');
    $this->assertEquals('Группа 1-2', $this->group->get_name());
  }

  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->group->set_name('English');
  }

  public function test_set_name_3(){
    $this->setExpectedException('DomainException');
    $this->group->set_name(str_repeat('Д', 51));
  }

  public function test_add_user_1(){
    $user = new user();
    $this->group->add_user($user);
    $users = $this->group->get_users();
    $this->assertCount(1, $users);
    $this->assertSame($user, $users[0]);
  }

  public function test_add_user_2(){
    $this->setExpectedException('DomainException');
    $user = new user();
    $this->group->add_user($user);
    $this->group->add_user($user);
  }

  public function test_exclude_user(){
    $user1 = new user();
    $user2 = new user();
    $this->group->add_user($user1);
    $this->group->add_user($user2);
    $users = $this->group->get_users();
    $this->assertCount(2, $users);
    $this->group->exclude_user($user1);
    $users = $this->group->get_users();
    $this->assertCount(1, $users);
    $this->assertSame($user2, $users[1]);
  }
}