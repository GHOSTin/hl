<?php

use \boxxy\classes\di;

class controller_works_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new model_request();
    $this->pimple = new \Pimple\Container();
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
      ->disableOriginalConstructor()->getMock();
  }

  public function test_private_show_default_page(){
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->getMock();
      $repository->expects($this->exactly(2))
        ->method('findAll')
        ->will($this->onConsecutiveCalls('wokgroup_array', 'work_array'));
      $this->em->expects($this->exactly(2))
        ->method('getRepository')
        ->withConsecutive(
          ['data_workgroup'],
          ['data_work'])
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_show_default_page($this->request);
    $this->assertEquals('wokgroup_array', $response['groups']);
    $this->assertEquals('work_array', $response['works']);
  }

  public function test_private_get_workgroup_content(){
    $this->request->set_property('id', 125);
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneById')
        ->with(125)
        ->will($this->returnValue('workgroup_object'));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_workgroup')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_get_workgroup_content($this->request);
    $this->assertEquals('workgroup_object', $response['workgroup']);
  }

  public function test_private_get_work_content(){
    $this->request->set_property('id', 125);
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneById')
        ->with(125)
        ->will($this->returnValue('work_object'));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_work')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_get_work_content($this->request);
    $this->assertEquals('work_object', $response['work']);
  }

  public function test_private_get_dialog_add_work(){
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->getMock();
      $repository->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue('work_array'));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_work')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_get_dialog_add_work($this->request);
    $this->assertEquals('work_array', $response['works']);
  }

  public function test_private_get_dialog_create_workgroup(){
    controller_works::private_get_dialog_create_workgroup($this->request);
  }

  public function test_private_get_dialog_create_work(){
    controller_works::private_get_dialog_create_work($this->request);
  }

  public function test_private_get_dialog_exclude_work(){
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->exactly(2))
        ->method('findOneById')
        ->will($this->onConsecutiveCalls('wokgroup_object', 'work_object'));
      $this->em->expects($this->exactly(2))
        ->method('getRepository')
        ->withConsecutive(['data_workgroup'], ['data_work'])
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_get_dialog_exclude_work($this->request);
    $this->assertEquals('wokgroup_object', $response['workgroup']);
    $this->assertEquals('work_object', $response['work']);
  }

  public function test_private_get_dialog_rename_work(){
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneById')
        ->will($this->returnValue('work_object'));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_work')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_get_dialog_rename_work($this->request);
    $this->assertEquals('work_object', $response['work']);
  }

  public function test_private_get_dialog_rename_workgroup(){
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneById')
        ->will($this->returnValue('workgroup_object'));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_workgroup')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_get_dialog_rename_workgroup($this->request);
    $this->assertEquals('workgroup_object', $response['workgroup']);
  }

  public function test_private_add_work(){
    $this->request->set_property('workgroup_id', 125);
    $this->request->set_property('work_id', 253);
    $workgroup = new data_workgroup();
    $work = new data_work();
    $this->pimple['em'] = function() use ($workgroup, $work){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->exactly(2))
        ->method('findOneById')
        ->withConsecutive([125], [253])
        ->will($this->onConsecutiveCalls($workgroup, $work));
      $this->em->expects($this->exactly(2))
        ->method('getRepository')
        ->withConsecutive(['data_workgroup'], ['data_work'])
        ->will($this->returnValue($repository));
      $this->em->expects($this->once())
        ->method('flush');;
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_add_work($this->request);
    $this->assertSame($workgroup, $response['workgroup']);
  }

  public function test_private_exclude_work(){
    $this->request->set_property('workgroup_id', 125);
    $this->request->set_property('work_id', 253);
    $workgroup = new data_workgroup();
    $work = new data_work();
    $this->pimple['em'] = function() use ($workgroup, $work){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->exactly(2))
        ->method('findOneById')
        ->withConsecutive([125], [253])
        ->will($this->onConsecutiveCalls($workgroup, $work));
      $this->em->expects($this->exactly(2))
        ->method('getRepository')
        ->withConsecutive(['data_workgroup'], ['data_work'])
        ->will($this->returnValue($repository));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_exclude_work($this->request);
    $this->assertSame($workgroup, $response['workgroup']);
  }

  public function test_private_rename_workgroup(){
    $this->request->set_property('workgroup_id', 125);
    $this->request->set_property('name', 'Привет');
    $workgroup = new data_workgroup();
    $this->pimple['em'] = function() use ($workgroup){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneById')
        ->with(125)
        ->will($this->returnValue($workgroup));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_workgroup')
        ->will($this->returnValue($repository));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_rename_workgroup($this->request);
    $this->assertSame($workgroup, $response['workgroup']);
    $this->assertSame('Привет', $response['workgroup']->get_name());
  }

  public function test_private_rename_work(){
    $this->request->set_property('id', 125);
    $this->request->set_property('name', 'Привет');
    $work = new data_work();
    $this->pimple['em'] = function() use ($work){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneById')
        ->with(125)
        ->will($this->returnValue($work));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_work')
        ->will($this->returnValue($repository));
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_rename_work($this->request);
    $this->assertSame($work, $response['work']);
    $this->assertEquals('Привет', $response['work']->get_name());
  }

  public function test_private_create_workgroup_1(){
    $this->request->set_property('name', 'Привет');
    $this->setExpectedException('RuntimeException');
    $workgroup = new data_workgroup();
    $this->pimple['em'] = function() use ($workgroup){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneByName'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneByName')
        ->with('Привет')
        ->will($this->returnValue($workgroup));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_workgroup')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_create_workgroup($this->request);
  }

  public function test_private_create_workgroup_2(){
    $this->request->set_property('name', 'Привет');
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneByName', 'findAll'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneByName')
        ->with('Привет')
        ->will($this->returnValue(null));
      $repository->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue('workgroup_array'));
      $this->em->expects($this->exactly(2))
        ->method('getRepository')
        ->with('data_workgroup')
        ->will($this->returnValue($repository));
      $this->em->expects($this->once())
        ->method('persist');
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_create_workgroup($this->request);
    $this->assertEquals('workgroup_array', $response['workgroups']);
  }

  public function test_private_create_work_1(){
    $this->request->set_property('name', 'Привет');
    $this->setExpectedException('RuntimeException');
    $work = new data_workgroup();
    $this->pimple['em'] = function() use ($work){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneByName'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneByName')
        ->with('Привет')
        ->will($this->returnValue($work));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_work')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_create_work($this->request);
  }

  public function test_private_create_work_2(){
    $this->request->set_property('name', 'Привет');
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneByName', 'findAll'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneByName')
        ->with('Привет')
        ->will($this->returnValue(null));
      $repository->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue('work_array'));
      $this->em->expects($this->exactly(2))
        ->method('getRepository')
        ->with('data_work')
        ->will($this->returnValue($repository));
      $this->em->expects($this->once())
        ->method('persist');
      $this->em->expects($this->once())
        ->method('flush');
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_works::private_create_work($this->request);
    $this->assertEquals('work_array', $response['works']);
  }
}