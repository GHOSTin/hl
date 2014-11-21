<?php

use \boxxy\classes\di;

class controller_number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new model_request();
    $this->pimple = new \Pimple\Container();
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
         ->disableOriginalConstructor()->getMock();
    $this->model_number = $this->getMockBuilder('model_number')
         ->disableOriginalConstructor()->getMock();
  }

  public function test_private_edit_department(){
    $house = new data_house();
    $department = new data_department();
    $this->pimple['em'] = function() use ($house, $department){
      $this->em->expects($this->exactly(2))
        ->method('find')
        ->withConsecutive([$this->equalTo('data_house')],
          [$this->equalTo('data_department')])
          ->will($this->onConsecutiveCalls($house, $department));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_edit_department($this->request);
    $this->assertSame($house, $response['house']);
  }

  public function test_private_show_default_page(){
    $street = new data_street();
    $this->pimple['em'] = function() use ($street){
      $er = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->getMock();
      $er->expects($this->once())
        ->method('findBy')
        ->will($this->returnValue([$street]));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->will($this->returnValue($er));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_show_default_page($this->request);
    $this->assertCount(1, $response);
    $this->assertContainsOnlyInstancesOf('data_street', $response['streets']);
  }

  public function test_private_get_street_content(){
    $this->pimple['model_number'] = function(){
      $this->model_number->expects($this->once())
        ->method('get_houses_by_street')
        ->will($this->returnValue('houses'));
      return $this->model_number;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_street_content($this->request);
    $this->assertEquals('houses', $response['houses']);
  }

  public function test_private_get_dialog_edit_department(){
    $house = new data_house();
    $department = new data_department();
    $this->pimple['em'] = function() use ($house, $department){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($house));
      $er = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->getMock();
      $er->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue([$department]));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_department')
        ->will($this->returnValue($er));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_dialog_edit_department($this->request);
    $this->assertInstanceOf('data_house', $response['house']);
    $this->assertCount(1, $response['departments']);
    $this->assertContainsOnlyInstancesOf('data_department', $response['departments']);
  }

  public function test_private_get_house_content(){
    $this->request->set_property('id', 125);
    $house = new data_house();
    $this->pimple['em'] = function() use ($house){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_house', 125)
        ->will($this->returnValue($house));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_house_content($this->request);
    $this->assertInstanceOf('data_house', $response['house']);
  }

  public function test_private_query_of_house(){
    $this->request->set_property('id', 125);
    $house = new data_house();
    $this->pimple['em'] = function() use ($house){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_house', 125)
        ->will($this->returnValue($house));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_query_of_house($this->request);
    $this->assertInstanceOf('data_house', $response['house']);
  }

  public function test_private_query_of_number(){
    $this->request->set_property('id', 125);
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_number', 125)
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_query_of_number($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_get_number(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_number', 125)
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::get_number(125);
    $this->assertInstanceOf('data_number', $response);
  }

  public function test_private_get_number_content(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_number_content($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_contact_info(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_contact_info($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_get_dialog_edit_number(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_dialog_edit_number($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_accruals(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_accruals($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_get_dialog_edit_number_fio(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_dialog_edit_number_fio($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_get_dialog_edit_password(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_dialog_edit_password($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_get_dialog_edit_number_cellphone(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_dialog_edit_number_cellphone($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_get_dialog_edit_number_telephone(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_dialog_edit_number_telephone($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_get_dialog_edit_number_email(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_dialog_edit_number_email($this->request);
    $this->assertInstanceOf('data_number', $response['number']);
  }

  public function test_private_update_number_email(){
    $this->request->set_property('email', 'nekrasov@mlsco.ru');
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $number = controller_number::private_update_number_email($this->request)['number'];
    $this->assertInstanceOf('data_number', $number);
    $this->assertEquals('nekrasov@mlsco.ru', $number->get_email());
  }

  public function test_private_update_number_telephone(){
    $this->request->set_property('telephone', '647957');
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $number = controller_number::private_update_number_telephone($this->request)['number'];
    $this->assertInstanceOf('data_number', $number);
    $this->assertEquals('647957', $number->get_telephone());
  }

  public function test_private_update_number_cellphone(){
    $this->request->set_property('cellphone', '+79222944742');
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $number = controller_number::private_update_number_cellphone($this->request)['number'];
    $this->assertInstanceOf('data_number', $number);
    $this->assertEquals('9222944742', $number->get_cellphone());
  }

  public function test_private_update_number_fio(){
    $this->request->set_property('fio', 'Некрасов Евгений');
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $number = controller_number::private_update_number_fio($this->request)['number'];
    $this->assertInstanceOf('data_number', $number);
    $this->assertEquals('Некрасов Евгений', $number->get_fio());
  }

  public function test_private_update_number_password_1(){
    $this->request->set_property('password', 'Aa12345678');
    $this->request->set_property('confirm', 'Aa12345678');
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $number = controller_number::private_update_number_password($this->request)['number'];
    $this->assertInstanceOf('data_number', $number);
    $this->assertEquals('7604aba942efa27a39e7a80dfd31fce5', $number->get_hash());
  }

  public function test_private_update_number_password_2(){
    $this->setExpectedException('RuntimeException');
    $this->request->set_property('password', 'Aa12345678');
    $this->request->set_property('confirm', 'Aa123456');
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->never())
        ->method('find');
      $this->em->expects($this->never());
      return $this->em;
    };
    di::set_instance($this->pimple);
    controller_number::private_update_number_password($this->request);
  }
}