<?php namespace client\models;

use Doctrine\ORM\EntityManager;
use Twig_Environment;
use Monolog\Logger;
use domain\registration_request;

class registration{

  private $em;
  private $twig;
  private $logger;

  public function __construct(Twig_Environment $twig, EntityManager $em, Logger $logger){
    $this->em = $em;
    $this->twig = $twig;
    $this->logger = $logger;
  }

  /**
   * Выводит форму регистрации в анонимном режиме личного кабинета
   */
  public function registration_form(){
    return $this->twig->render('registration/registration_form.tpl', ['number' => null]);
  }

  /**
   * Создает запрос на доступ и выдает страницу об успешном создании запроса
   * либо выводит страницу об ошибке что лицевого счета не существует в системе
   */
  public function create_request($number, $fio, $address, $email, $tellephone, $cellphone){
    $context = [
      'number' => $number,
      'fio' => $fio,
      'address' => $address,
      'email' => $email,
      'tellephone' => $tellephone,
      'cellphone' => $cellphone
    ];
    $this->logger->addInfo('Begin registration', $context);
    $num = $this->em->getRepository('domain\number')
                    ->findOneByNumber($number);
    if(!$num){
      $this->logger->addWarning('Not found number for registration', $context);
      return $this->twig->render('registration/not_found_number.tpl', ['number' => $number]);
    }
    $request = new registration_request($num, $fio, $address, $email, $tellephone, $cellphone);
    $this->em->persist($request);
    $this->em->flush();
    $this->logger->addInfo('Create registration request', $context);
    return $this->twig->render('registration/success.tpl');
  }
}