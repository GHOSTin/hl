<?php

class mapper_user_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->pimple = new \Pimple\Container();
    $this->row = [125];
  }

  public function test_find_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->find(123);
  }

  public function test_find_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(2));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->find(125);
  }

  public function test_find_3(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $user = (new mapper_user($this->pdo))
      ->find(125);
    $this->assertNull($user);
  }

  public function test_find_4(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(1));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue($this->row));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->pimple['factory_user'] = function($p){
      $factory = $this->getMock('factory_user');
      $factory->expects($this->once())
        ->method('build')
        ->will($this->returnValue(new data_user()));
      return $factory;
    };
    di::set_instance($this->pimple);
    $user = (new mapper_user($this->pdo))
      ->find(125);
    $this->assertInstanceOf('data_user', $user);
  }

  public function test_find_by_login_and_password_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->find_by_login_and_password('NekrasovEV', 'Aa123456');
  }

  public function test_find_by_login_and_password_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(2));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->find_by_login_and_password('NekrasovEV', 'Aa123456');
  }

  public function test_find_by_login_and_password_3(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $user = (new mapper_user($this->pdo))
      ->find_by_login_and_password('NekrasovEV', 'Aa123456');
    $this->assertNull($user);
  }

  public function test_find_by_login_and_password_4(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(1));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue($this->row));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->pimple['factory_user'] = function($p){
      $factory = $this->getMock('factory_user');
      $factory->expects($this->once())
        ->method('build')
        ->will($this->returnValue(new data_user()));
      return $factory;
    };
    di::set_instance($this->pimple);
    $user = (new mapper_user($this->pdo))
      ->find_by_login_and_password('NekrasovEV', 'Aa123456');
    $this->assertInstanceOf('data_user', $user);
  }

  public function test_get_insert_id_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->get_insert_id();
  }

  public function test_get_insert_id_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue(['max_user_id' => '124']));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $id = (new mapper_user($this->pdo))
      ->get_insert_id();
    $this->assertEquals(125, $id);
  }

  public function test_get_users_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->get_users();
  }


  public function test_get_users_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls($this->row, $this->row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->pimple['factory_user'] = function($p){
      $factory = $this->getMock('factory_user');
      $factory->expects($this->exactly(2))
        ->method('build')
        ->will($this->returnValue(new data_user()));
      return $factory;
    };
    di::set_instance($this->pimple);
    $users = (new mapper_user($this->pdo))
      ->get_users();
    $this->assertCount(2, $users);
    $this->assertContainsOnlyInstancesOf('data_user', $users);
  }

  public function test_find_by_login_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->find_by_login('NekrasovEV');
  }

    public function test_find_by_login_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(2));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_user($this->pdo))
      ->find_by_login('NekrasovEV');
  }

  public function test_find_by_login_3(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $user = (new mapper_user($this->pdo))
      ->find_by_login('NekrasovEV');
    $this->assertNull($user);
  }

  public function test_find_by_login_4(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(1));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue($this->row));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->pimple['factory_user'] = function($p){
      $factory = $this->getMock('factory_user');
      $factory->expects($this->once())
        ->method('build')
        ->will($this->returnValue(new data_user()));
      return $factory;
    };
    di::set_instance($this->pimple);
    $user = (new mapper_user($this->pdo))
      ->find_by_login('NekrasovEV');
    $this->assertInstanceOf('data_user', $user);
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $user = $this->getMock('data_user');
    $user->expects($this->once())
      ->method('get_id');
    $user->expects($this->once())
      ->method('get_status');
    $user->expects($this->once())
      ->method('get_login');
    $user->expects($this->once())
      ->method('get_firstname');
    $user->expects($this->once())
      ->method('get_lastname');
    $user->expects($this->once())
      ->method('get_middlename');
    $user->expects($this->once())
      ->method('get_hash');
    $user->expects($this->once())
      ->method('get_telephone');
    $user->expects($this->once())
      ->method('get_cellphone');
    (new mapper_user($this->pdo))
      ->insert($user);
  }

  public function test_update_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $user = $this->getMock('data_user');
    $user->expects($this->once())
      ->method('get_id');
    $user->expects($this->once())
      ->method('get_status');
    $user->expects($this->once())
      ->method('get_login');
    $user->expects($this->once())
      ->method('get_firstname');
    $user->expects($this->once())
      ->method('get_lastname');
    $user->expects($this->once())
      ->method('get_middlename');
    $user->expects($this->once())
      ->method('get_hash');
    $user->expects($this->once())
      ->method('get_telephone');
    $user->expects($this->once())
      ->method('get_cellphone');
    (new mapper_user($this->pdo))
      ->update($user);
  }
}