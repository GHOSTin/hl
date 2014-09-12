<?php
class controller_number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new model_request();
    $this->pimple = new \Pimple\Container();
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
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
    $street = new data_street();
    $this->pimple['em'] = function() use ($street){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($street));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_street_content($this->request);
    $this->assertInstanceOf('data_street', $response['street']);
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
    $house = new data_house();
    $this->pimple['em'] = function() use ($house){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($house));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_house_content($this->request);
    $this->assertInstanceOf('data_house', $response['house']);
  }

  public function test_private_get_house_information(){
    $house = new data_house();
    $this->pimple['em'] = function() use ($house){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($house));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_house_information($this->request);
    $this->assertInstanceOf('data_house', $response['house']);
  }

  public function test_private_get_house_numbers(){
    $house = new data_house();
    $this->pimple['em'] = function() use ($house){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($house));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_house_numbers($this->request);
    $this->assertInstanceOf('data_house', $response['house']);
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

  public function test_private_get_number_information(){
    $number = new data_number();
    $this->pimple['em'] = function() use ($number){
      $this->em->expects($this->once())
        ->method('find')
        ->will($this->returnValue($number));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_number::private_get_number_information($this->request);
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
}