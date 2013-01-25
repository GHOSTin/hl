CREATE TABLE IF NOT EXISTS `cities` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `indx_name` (`company_id`,`name`),
  UNIQUE KEY `company_id` (`company_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cities__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `city_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `city_id` (`city_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `companies` (
  `id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  `smslogin` VARCHAR(255) NOT NULL,
  `smspassword` VARCHAR(255) NOT NULL,
  `smssender` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `companies__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL, 
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ctps` (
  `ctp_id` SMALLINT(5) UNSIGNED NOT NULL,
  `ctp_city_id` TINYINT(3) UNSIGNED NOT NULL,
  `ctp_name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `indx_id` (`ctp_city_id`,`ctp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `departments` (
  `id` TINYINT(3) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('active','deactive') NOT NULL DEFAULT 'active',
  `name` VARCHAR(255) NOT NULL,
  KEY `id` (`company_id`,`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flats` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `house_id` SMALLINT(5) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `flatnumber` VARCHAR(32) NOT NULL,
  UNIQUE KEY `company_id` (`company_id`,`id`),
  UNIQUE KEY `company_id_2` (`company_id`,`house_id`,`flatnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flats__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `flat_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `flat_id` (`flat_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `group2user` (
  `group_id` SMALLINT(5) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indx_name` (`company_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groups__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `group_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create','add') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `houses` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `city_id` SMALLINT(5) UNSIGNED NOT NULL,
  `street_id` SMALLINT(5) UNSIGNED NOT NULL,
  `department_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `housENUMber` VARCHAR(32) NOT NULL,
  UNIQUE KEY `company_id` (`company_id`,`id`),
  UNIQUE KEY `company_id_2` (`company_id`,`street_id`,`housENUMber`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `houses__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `house_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `house_id` (`house_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `materialgroups` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `indx_name` (`company_id`,`name`),
  UNIQUE KEY `company_id` (`company_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `materialgroups__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `materialgroup_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create','material_add') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `materialclass_id` (`materialgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `materials` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `materialgroup_id` SMALLINT(5) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  `unit` VARCHAR(255) DEFAULT NULL,
  UNIQUE KEY `company_id` (`company_id`,`id`),
  UNIQUE KEY `company_id_2` (`company_id`,`materialgroup_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `materials__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `material_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `material_id` (`material_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `meter2data` (
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `number_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `meter_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `serial` VARCHAR(255) NOT NULL,
  `time` INT(10) UNSIGNED NOT NULL,
  `value` TEXT NOT NULL,
  KEY `id` (`company_id`,`number_id`),
  KEY `serial` (`serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `meters` (
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  `service_id` SMALLINT(5) UNSIGNED NOT NULL,
  UNIQUE KEY `id` (`company_id`,`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `number2meter` (
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `number_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `meter_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `service_id` SMALLINT(5) UNSIGNED NOT NULL,
  `serial` VARCHAR(255) NOT NULL,
  `checktime` INT(10) UNSIGNED NOT NULL,
  KEY `id` (`company_id`,`number_id`,`meter_id`),
  KEY `serial` (`serial`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `numbers` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `city_id` SMALLINT(5) UNSIGNED NOT NULL,
  `house_id` SMALLINT(5) UNSIGNED NOT NULL,
  `flat_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `number` VARCHAR(20) NOT NULL,
  `type` ENUM('human','organization','house') NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `fio` VARCHAR(255) DEFAULT NULL,
  `telephone` VARCHAR(11) DEFAULT NULL,
  `cellphone` VARCHAR(11) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `contact-fio` VARCHAR(255) DEFAULT NULL,
  `contact-telephone` VARCHAR(11) DEFAULT NULL,
  `contact-cellphone` VARCHAR(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `number` (`number`),
  KEY `password` (`password`),
  KEY `company_id` (`company_id`),
  KEY `flat_id` (`flat_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `numbersessions` (
  `hash` VARCHAR(255) NOT NULL,
  `number_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `ipaddress` VARCHAR(200) NOT NULL,
  `time` BIGINT(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `numbers__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `number_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `number_id` (`number_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `phrases` (
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `tag` ENUM('query_description') NOT NULL,
  `time` INT(10) UNSIGNED NOT NULL,
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `phrase` TEXT NOT NULL,
  `parent_id` INT(5) UNSIGNED NOT NULL,
  UNIQUE KEY `id` (`company_id`,`time`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `profiles` (
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `profile` VARCHAR(255) NOT NULL,
  `rules` TEXT NOT NULL,
  `restrictions` TEXT NOT NULL,
  `settings` TEXT NOT NULL,
  UNIQUE KEY `profile` (`company_id`,`user_id`,`profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `queries` (
  `id` INT(10) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('close','open','reopen','working') NOT NULL DEFAULT 'open',
  `initiator-type` ENUM('number','house') NOT NULL,
  `payment-status` ENUM('paid','unpaid','recalculation') NOT NULL DEFAULT 'unpaid',
  `warning-type` ENUM('hight','normal','planned') NOT NULL DEFAULT 'normal',
  `department_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  `house_id` SMALLINT(5) UNSIGNED NOT NULL,
  `query_close_reason_id` TINYINT(3) UNSIGNED DEFAULT NULL,
  `query_worktype_id` TINYINT(3) UNSIGNED NOT NULL,
  `opentime` INT(10) UNSIGNED NOT NULL,
  `worktime` INT(10) UNSIGNED DEFAULT NULL,
  `closetime` INT(10) UNSIGNED DEFAULT NULL,
  `addinfo-name` VARCHAR(255) DEFAULT NULL,
  `addinfo-telephone` VARCHAR(11) DEFAULT NULL,
  `addinfo-cellphone` VARCHAR(11) DEFAULT NULL,
  `description-open` TEXT,
  `description-close` TEXT,
  `querynumber` MEDIUMINT(8) UNSIGNED NOT NULL,
  `query_inspection` TEXT NOT NULL,
  UNIQUE KEY `company_id_2` (`company_id`,`id`),
  KEY `status` (`status`),
  KEY `opentime` (`opentime`),
  KEY `closetime` (`closetime`),
  KEY `query_close_reason_id` (`query_close_reason_id`),
  KEY `query_worktype` (`query_worktype_id`),
  KEY `initiator-type` (`initiator-type`),
  KEY `department_id` (`department_id`),
  KEY `house_id` (`house_id`),
  KEY `warning-type` (`warning-type`),
  KEY `worktime` (`worktime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `queries__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `query_id` INT(10) UNSIGNED NOT NULL,
  `code` ENUM('error','open','close','number_add','number_del','creator_add','creator_del','manager_add','manager_del','performer_add','performer_del','observer_add','observer_del','work_add','work_del','work_upd','material_add','material_del','material_upd','payment-status_upd','house_del') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `query_id` (`query_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `query2material` (
  `query_id` INT(10) UNSIGNED NOT NULL,
  `material_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `amount` decimal(10,2) DEFAULT '0.00',
  `value` decimal(10,2) DEFAULT '0.00',
  KEY `query_id` (`query_id`),
  KEY `material_id` (`material_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `query2number` (
  `query_id` INT(10) UNSIGNED NOT NULL,
  `number_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `default` ENUM('false','true') NOT NULL DEFAULT 'true',
  KEY `query_id` (`query_id`),
  KEY `number_id` (`number_id`),
  KEY `company_id` (`company_id`),
  KEY `company_id_2` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `query2user` (
  `query_id` INT(10) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `class` ENUM('controller','creator','manager','observer','performer') NOT NULL,
  `protect` ENUM('FALSE','TRUE') NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `query_id` (`query_id`),
  KEY `class` (`class`,`protect`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `query2work` (
  `query_id` INT(10) UNSIGNED NOT NULL,
  `work_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `opentime` INT(10) UNSIGNED NOT NULL,
  `closetime` INT(10) UNSIGNED NOT NULL,
  `value` decimal(10,2) DEFAULT '0.00',
  KEY `query_id` (`query_id`),
  KEY `work_id` (`work_id`),
  KEY `company_id` (`company_id`),
  KEY `opentime` (`opentime`),
  KEY `closetime` (`closetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `query_close_reasons` (
  `id` TINYINT(3) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('active','deactive') NOT NULL DEFAULT 'active',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `id` (`company_id`,`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `query_worktypes` (
  `id` TINYINT(3) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('active','deactive') NOT NULL DEFAULT 'active',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `id` (`company_id`,`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `services` (
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `tag` ENUM('meter') NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `id` (`company_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sessions` (
  `hash` CHAR(255) NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `ipaddress` VARCHAR(200) NOT NULL,
  `time` INT(10) UNSIGNED NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sessions__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL,
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sms` (
  `id` INT(10) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `time` INT(10) UNSIGNED NOT NULL,
  `timeday` INT(10) UNSIGNED NOT NULL,
  `timemonth` INT(10) UNSIGNED NOT NULL,
  `timeyear` INT(10) UNSIGNED NOT NULL,
  `status` ENUM('new','delivered','send','failure') NOT NULL DEFAULT 'new',
  `type` ENUM('user','number') NOT NULL,
  `message` TEXT NOT NULL,
  KEY `status` (`status`),
  KEY `company_id` (`company_id`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `time` (`time`),
  KEY `timeday` (`timeday`),
  KEY `timemonth` (`timemonth`),
  KEY `timeyear` (`timeyear`),
  KEY `type` (`type`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sms2number` (
  `sms_id` INT(10) UNSIGNED NOT NULL,
  `number_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `timeyear` INT(10) UNSIGNED NOT NULL,
  KEY `sms_id` (`sms_id`),
  KEY `number_id` (`number_id`),
  KEY `company_id` (`company_id`),
  KEY `timeyear` (`timeyear`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sms2query` (
  `sms_id` INT(10) UNSIGNED NOT NULL,
  `query_id` INT(10) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `timeyear` INT(10) UNSIGNED NOT NULL,
  KEY `sms_id` (`sms_id`),
  KEY `query_id` (`query_id`),
  KEY `company_id` (`company_id`),
  KEY `timeyear` (`timeyear`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sms2user` (
  `sms_id` INT(10) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `timeyear` INT(10) UNSIGNED NOT NULL,
  KEY `sms_id` (`sms_id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `timeyear` (`timeyear`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `smsgroup2number` (
  `smsgroup_id` SMALLINT(5) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `number_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `house_id` SMALLINT(5) UNSIGNED NOT NULL,
  KEY `smsgroup_id` (`smsgroup_id`),
  KEY `number_id` (`number_id`),
  KEY `house_id` (`house_id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `smsgroups` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `company_id` (`company_id`,`user_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `smsgroups__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `smsgroup_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create','number_add') NOT NULL,
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `id` (`company_id`,`smsgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sms__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `sms_id` INT(10) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `sms_id` (`sms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `streets` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `city_id` SMALLINT(5) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `id` (`company_id`,`id`),
  UNIQUE KEY `name` (`company_id`,`city_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `streets__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `street_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `id` (`company_id`,`street_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tasks` (
  `task_company_id` TINYINT(3) UNSIGNED NOT NULL,
  `task_tree_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `task_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `task_parent_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `task_left_key` SMALLINT(5) UNSIGNED NOT NULL,
  `task_right_key` SMALLINT(5) UNSIGNED NOT NULL,
  `task_level` TINYINT(3) UNSIGNED NOT NULL,
  `task_open_time` INT(10) UNSIGNED NOT NULL,
  `task_target_time` INT(10) UNSIGNED NOT NULL,
  `task_close_time` INT(10) UNSIGNED DEFAULT NULL,
  `task_type` ENUM('department','user') NOT NULL,
  `task_type_id` TINYINT(3) UNSIGNED NOT NULL,
  `task_subtask_counter` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `task_subtask_percents` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  `task_percents` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `task_theme` VARCHAR(255) DEFAULT NULL,
  `task_description` TEXT,
  `task_close_reason` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `task_comments` (
  `task_comment_id` SMALLINT(5) UNSIGNED NOT NULL,
  `task_comment_task_id` INT(11) NOT NULL,
  `task_comment_company_id` TINYINT(3) UNSIGNED NOT NULL,
  `task_comment_user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `task_comment_status` ENUM('active','deactive') NOT NULL,
  `task_comment_time` INT(11) NOT NULL,
  `task_comment_TEXT` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `task_types` (
  `task_type_id` SMALLINT(5) UNSIGNED NOT NULL,
  `task_type_company_id` TINYINT(3) UNSIGNED NOT NULL,
  `task_type_status` ENUM('active','deactive') NOT NULL,
  `task_type_name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('true','false') NOT NULL DEFAULT 'true',
  `username` VARCHAR(255) NOT NULL,
  `firstname` VARCHAR(255) DEFAULT NULL,
  `lastname` VARCHAR(255) DEFAULT NULL,
  `midlename` VARCHAR(255) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `telephone` VARCHAR(11) DEFAULT NULL,
  `cellphone` VARCHAR(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indx_username` (`company_id`,`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL DEFAULT 'error',
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `workgroups` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `status` ENUM('active','deactive') NOT NULL DEFAULT 'active',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `company_id` (`company_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `workgroups__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `workgroup_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create','work_add') NOT NULL,
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `id` (`company_id`,`workgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `works` (
  `id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `workgroup_id` SMALLINT(5) UNSIGNED NOT NULL,
  `status` ENUM('active','deactive') NOT NULL DEFAULT 'active',
  `name` VARCHAR(255) NOT NULL,
  UNIQUE KEY `id` (`company_id`,`id`),
  UNIQUE KEY `workgroup_id` (`company_id`,`workgroup_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `works__log` (
  `time` INT(10) UNSIGNED NOT NULL,
  `loginuser_id` SMALLINT(5) UNSIGNED NOT NULL,
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `work_id` SMALLINT(5) UNSIGNED NOT NULL,
  `code` ENUM('error','create') NOT NULL,
  `message` MEDIUMTEXT NOT NULL,
  KEY `code` (`code`),
  KEY `loginuser_id` (`loginuser_id`),
  KEY `id` (`company_id`,`work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;