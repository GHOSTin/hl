<?php
class controller_error_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new model_request();
    $this->pimple = new \Pimple\Container();
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
      ->disableOriginalConstructor()->getMock();
  }

  public function test_error404(){
    $this->assertNull(controller_error::error404($this->request));
  }

  public function test_private_show_dialog(){
    $this->assertNull(controller_error::private_show_dialog($this->request));
  }

  public function test_private_delete_error_1(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['em'] = function(){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_error');
      $this->em->expects($this->never())
        ->method('remove');
      $this->em->expects($this->never())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    controller_error::private_delete_error($this->request);
  }

  public function test_private_delete_error_2(){
    $error = new data_error();
    $this->pimple['em'] = function() use ($error){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_error')
        ->will($this->returnValue($error));
      $this->em->expects($this->once())
        ->method('remove')
        ->with($error);
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    controller_error::private_delete_error($this->request);
  }

  public function test_private_send_error_1(){
    $this->pimple['em'] = function(){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_error');
      $this->em->expects($this->once())
        ->method('persist');
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    $this->pimple['user'] = new data_user();
    di::set_instance($this->pimple);
    controller_error::private_send_error($this->request);
  }

  public function test_private_send_error_2(){
    $this->setExpectedException('RuntimeException');
    $error = new data_error();
    $this->pimple['em'] = function() use ($error){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_error')
        ->will($this->returnValue($error));
      $this->em->expects($this->never())
        ->method('persist');
      $this->em->expects($this->never())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    controller_error::private_send_error($this->request);
  }

  public function test_private_show_default_page(){
    $error = new data_error();
    $this->pimple['em'] = function() use ($error){
      $er = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->getMock();
      $er->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue([$error]));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->will($this->returnValue($er));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $errors = controller_error::private_show_default_page($this->request);
    $this->assertCount(1, $errors);
    $this->assertContainsOnlyInstancesOf('data_error', $errors['errors']);
  }
}