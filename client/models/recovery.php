<?php namespace client\models;

use Doctrine\ORM\EntityManager;
use Twig_Environment;
use Monolog\Logger;
use domain\number;

class recovery{

  private $em;
  private $twig;

  public function __construct(Twig_Environment $twig, EntityManager $em, Logger $logger){
    $this->em = $em;
    $this->twig = $twig;
    $this->logger = $logger;
  }

  public function recovery_form(){
    return $this->twig->render('recovery/default_page.tpl');
  }

  public function recovery($num, $salt, $message, $mailer, $reply, $context, $site_url){
    $number = $this->em->getRepository('domain\number')->findOneByNumber($num);
    if(is_null($number)){
      $this->logger->addWarning('Recovery number not exists', $context);
      return $this->twig->render('recovery/not_found_number.tpl', ['number' => $num]);
    }
    $email = $number->get_email();
    if(empty($email)){
      $this->logger->addWarning('Recovery email not exists', $context);
      return $this->twig->render('recovery/email_not_exists.tpl', ['number' => $num]);
    }
    $password = substr(sha1(time().$salt), 0, 8);
    $number->set_hash(number::generate_hash($password, $salt));
    $this->em->flush();
    $body = $this->twig->render('recovery\generate_password.tpl',
                                [
                                 'number' => $number,
                                 'password' => $password,
                                 'site_url' => $site_url
                                ]);
    $message->setSubject('Востановление пароля')
            ->setFrom([$reply])
            ->setTo([$email])
            ->setBody($body);
    $mailer->send($message);
    $this->logger->addInfo('Recovery success', $context);
    return $this->twig->render('recovery/success.tpl', ['number' => null]);
  }
}