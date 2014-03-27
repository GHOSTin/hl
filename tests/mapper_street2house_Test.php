<?php

class mapper_street2house_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->street = $this->getMock('data_street');
    $this->house = $this->getMock('data_house');
    $pimple = new Pimple();
    $pimple['factory_house'] = function($p){
      return new factory_house();
    };
    $street = new data_street();
    $this->row = ['id' => 5, 'number' => '57А', 'status' => 'false',
      'street' => $street, 'city_id' => 7];
    di::set_instance($pimple);
  }

  public function test_get_houses_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->once())
      ->method('get_id');
    (new mapper_street2house($this->pdo))
      ->get_houses($this->street);
  }

  public function test_get_houses_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls($this->row, $this->row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->once())
      ->method('get_id');
    $res = (new mapper_street2house($this->pdo))
      ->get_houses($this->street);
    $this->assertCount(2, $res);
  }

  public function test_init_houses_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->once())
      ->method('get_id');
    (new mapper_street2house($this->pdo))
      ->init_houses($this->street);
  }

  public function test_init_houses_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls($this->row, $this->row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->exactly(2))
      ->method('add_house');
    $this->street->expects($this->once())
      ->method('get_id');
    $res = (new mapper_street2house($this->pdo))
      ->init_houses($this->street);
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->house->expects($this->once())
      ->method('get_id');
    $this->house->expects($this->once())
      ->method('get_number');
    $this->street->expects($this->once())
      ->method('get_id');
    (new mapper_street2house($this->pdo))
      ->insert($this->street, $this->house);
  }

  public function test_insert_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->house->expects($this->once())
      ->method('get_id');
    $this->house->expects($this->once())
      ->method('get_number');
    $this->street->expects($this->once())
      ->method('get_id');
    $res = (new mapper_street2house($this->pdo))
      ->insert($this->street, $this->house);
    $this->assertSame($this->house, $res);
  }

  public function test_find_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->once())
      ->method('get_id');
    (new mapper_street2house($this->pdo))
      ->find($this->street, 5);
  }

  public function test_find_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->once())
      ->method('get_id');
    $res = (new mapper_street2house($this->pdo))
      ->find($this->street, 5);
    $this->assertNull($res);
  }

  public function test_find_3(){
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
    $this->street->expects($this->once())
      ->method('get_id');
    (new mapper_street2house($this->pdo))
      ->find($this->street, 5);
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
    $this->street->expects($this->once())
      ->method('get_id');
    $res = (new mapper_street2house($this->pdo))
      ->find($this->street, 5);
    $this->assertInstanceOf('data_house', $res);
  }

  public function test_find_by_number_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->once())
      ->method('get_id');
    (new mapper_street2house($this->pdo))
      ->find_by_number($this->street, '57А');
  }

    public function test_find_by_number_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->street->expects($this->once())
      ->method('get_id');
    $res = (new mapper_street2house($this->pdo))
      ->find_by_number($this->street, '57А');
    $this->assertNull($res);
  }

  public function test_find_by_number_3(){
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
    $this->street->expects($this->once())
      ->method('get_id');
    (new mapper_street2house($this->pdo))
      ->find_by_number($this->street, '57А');
  }

  public function test_find_by_number_4(){
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
    $this->street->expects($this->once())
      ->method('get_id');
    $res = (new mapper_street2house($this->pdo))
      ->find_by_number($this->street, '57А');
    $this->assertInstanceOf('data_house', $res);
  }

  public function test_get_insert_id_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_street2house($this->pdo))
      ->get_insert_id();
  }

  public function test_get_insert_id_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_street2house($this->pdo))
      ->get_insert_id();
  }

  public function test_get_insert_id_3(){
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
    (new mapper_street2house($this->pdo))
      ->get_insert_id();
  }

  public function test_get_insert_id_4(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(1));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue(['max_house_id' => 6]));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $res = (new mapper_street2house($this->pdo))
      ->get_insert_id();
    $this->assertEquals(7, $res);
  }
}