<?php namespace config;

class default_config{

  /**
  * Таймзона
  */
  const php_timezone = 'Asia/Yekaterinburg';

  // db option
  const db_name = 'test';
  const db_host = 'localhost';
  const db_user = 'test';
  const db_password = 'test';
  // auth option
  const authSalt = 'salt';

  const status = 'production';

  /**
  * Email, на который приходят заявки на доступ
  */
  const email_for_registration = 'mail@example.com';

  /**
  * Email, используемый для ответа
  */
  const email_for_reply = 'mail@example.com';
  const email_for_reply_password = 'ZYtLjdthz.Ct,t';

  const chat_enable = false;
  const chat_host = 'example.com';
  const chat_port = 3000;

  const debt_limit = 10000;
}