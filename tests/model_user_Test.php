<?php

class model_user_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->mapper = $this->getMockBuilder('mapper_user')
      ->disableOriginalConstructor()
      ->getMock();
    $this->pimple = new \Pimple\Container();
  }

  public function test_create_user_1(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_user'] = function(){
      $this->mapper->expects($this->once())
        ->method('find_by_login')
        ->will($this->returnValue(new data_user()));
      return $this->mapper;
    };
    di::set_instance($this->pimple);
    (new model_user)->create_user('Константинопольский', 'Константин',
                            'Константинович', 'Konstantin', 'Aa123456', 'true');
  }

  public function test_create_user_2(){
    $user = new data_user();
    $this->pimple['mapper_user'] = function() use ($user){
      $this->mapper->expects($this->once())
        ->method('find_by_login')
        ->will($this->returnValue(null));
      $this->mapper->expects($this->once())
        ->method('insert')
        ->will($this->returnValue($user));
      $this->mapper->expects($this->once())
        ->method('get_insert_id')
        ->will($this->returnValue(125));
      return $this->mapper;
    };
    $this->pimple['factory_user'] = function() use ($user){
      $factory = $this->getMock('factory_user');
      $factory->expects($this->once())
        ->method('build')
        ->will($this->returnValue($user));
      return $factory;
    };
    di::set_instance($this->pimple);
    $u = (new model_user)->create_user('Константинопольский', 'Константин',
                            'Константинович', 'Konstantin', 'Aa123456', 'true');
    $this->assertSame($user, $u);
  }
}