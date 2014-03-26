<?php

class mapper_query2comment_Test extends PHPUnit_Framework_TestCase{

  public static $query_row = ['id' => 5, 'lastname' => 'Некрасов',
    'firstname' => 'Евгений', 'midlename' => 'Валерьевич',
    'time' => 12345678, 'message' => 'Текст комментария'];

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->company = $this->getMock('data_company');
    $this->company->expects($this->once())
      ->method('get_id');
    $this->query = $this->getMock('data_query');
    $this->query->expects($this->once())
      ->method('get_id');
    $this->user = $this->getMock('data_user');
    $this->query2comment = $this->getMockBuilder('data_query2comment')
      ->disableOriginalConstructor()
      ->getMock();
    $pimple = new Pimple();
    $pimple['factory_query2comment'] = function($p){
      return new factory_query2comment();
    };
    di::set_instance($pimple);
  }

  public function test_find_all_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query2comment($this->pdo))
      ->find_all($this->company, $this->query);
  }

  public function test_find_all_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls(self::$query_row, self::$query_row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $comments = (new mapper_query2comment($this->pdo))
      ->find_all($this->company, $this->query);
      $this->assertCount(2, $comments);
  }

  public function test_init_comments_1(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(1))
      ->method('fetch')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->query->expects($this->never())
      ->method('add_comment');
    (new mapper_query2comment($this->pdo))
      ->init_comments($this->company, $this->query);
  }

  public function test_init_comments_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls(self::$query_row, self::$query_row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->query->expects($this->exactly(2))
      ->method('add_comment');
    (new mapper_query2comment($this->pdo))
      ->init_comments($this->company, $this->query);
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->user->expects($this->once())
      ->method('get_id');
    $this->query2comment->expects($this->once())
      ->method('get_user')
      ->will($this->returnValue($this->user));
    $this->query2comment->expects($this->once())
      ->method('get_time');
    $this->query2comment->expects($this->once())
      ->method('get_message');
    (new mapper_query2comment($this->pdo))
      ->insert($this->company, $this->query, $this->query2comment);
  }

  public function test_insert_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->user->expects($this->once())
      ->method('get_id');
    $this->query2comment->expects($this->once())
      ->method('get_user')
      ->will($this->returnValue($this->user));
    $this->query2comment->expects($this->once())
      ->method('get_time');
    $this->query2comment->expects($this->once())
      ->method('get_message');
    (new mapper_query2comment($this->pdo))
      ->insert($this->company, $this->query, $this->query2comment);
  }

  public function test_update_1(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls(self::$query_row, self::$query_row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->query->expects($this->once())
      ->method('get_comments')
      ->will($this->returnValue([]));
    (new mapper_query2comment($this->pdo))
      ->update($this->company, $this->query);
  }
}