cities
	id
	DELETEcompany_id
	status
	name

cities__log
	time
	loginuser
	company_id
	city_id
	code
	message

companies
	id
	status
	name
	smslogin
	smspassword
	smssender

companies__log
	time
	loginuser_id
	company_id
	code
	message

ctps
	ctp_id
	ctp_city_id
	ctp_name

departments
	id
	company_id
	status
	name

flats
	id
	DELETEcompany_id
	house_id
	status
	flatnumber

flats__log
	time
	loginuser_id
	company_id
	flat_id
	code
	message

group2user
	group_id
	user_id

groups
	id
	company_id
	status
	name

groups__log
	time
	loginuser_id
	group_id
	code
	message

houses
	id
	DELETEcompany_id
	city_id
	street_id
	department_id
	status
	housenumber

houses__log
	time
	loginuser
	company_id
	house_id
	code
	message

materialgroups
	id
	company_id
	status
	name

materialgroups__log
	time
	loginuser_id
	company_id
	materialgroup_id
	code
	message

materials
	id
	company_id
	materialgroup_id
	status
	name
	unit

materials__log
	time
	loginuser_id
	company_id
	material_id
	code
	message

meter2data
	company_id
	number_id
	meter_id
	serial
	time
	value

meters
	company_id
	id
	status
	name
	service_id

number2meter
	company_id
	number_id
	meter_id
	service_id
	serial
	checktime

numbers
	id
	company_id
	city_id
	house_id
	flat_id
	number
	type
	status
	fio
	telephone
	cellphone
	password
	contact-fio
	contact-telephone
	contact-cellphone

numbersessions
	hash
	number_id
	ipaddress
	time

number__log
	time
	loginuser_id
	company_id
	number_id
	code
	message

phrases
	company_id
	tag
	time
	id
	phrase
	parent_id

profiles
	company_id
	user_id
	profile
	rules
	restrictions
	settings

queries
	id
	company_id
	status
	initiator-type
	payment-status
	warning-type
	department_id
	house_id
	query_close_reason_id
	query_worktype_id
	opentime
	worktime
	closetime
	addinfo-name
	addinfo-telephone
	addinfo-cellphone
	description-open
	description-close
	querynumber
	query_inspection


queries__log
	time
	loginuser_id
	company_id
	query_id
	code
	message

query2material
	query_id
	material_id
	company_id
	amount
	value

query2number
	query_id
	number_id
	company_id
	default

query2user
	query_id
	user_id
	company_id
	class
	protect

query2work
	query_id
	work_id
	company_id
	opentime
	closetime
	value

query_close_reasons
	id
	company_id
	status
	name

query_worktypes
	id
	company_id
	status
	name

receipts
	id
	number_id
	time

receipts__log
	time
	loginuser_id
	receipt_id
	code
	message


services
	company_id
	id
	tag
	name

sessions
	hash
	user_id
	ipaddress
	time

sessions__log
	time
	user_id
	code
	message

sms
	id
	company_id
	loginuser_id
	time
	timeday
	timemonth
	timeyear
	status
	type
	message

sms2number
	sms_id
	number_id
	company_id
	timeyear

sms2query
	sms_id
	query_id
	company_id
	timeyear

sms2user
	sms_id
	user_id
	company_id
	timeyear

smsgroup2number
	smsgroup_id
	user_id
	company_id
	number_id
	house_id

smsgroups
	id
	user_id
	company_id
	status
	name

smsgroups__log
	time
	loginuser_id
	company_id
	smsgroup_id
	code
	message

sms__log
	time
	loginuser_id
	sms_id
	code
	message

streets
	id
	company_id
	city_id
	status
	name

streets__log
	time
	loginuser_id
	company_id
	street_id
	code
	message


tasks
	task_company_id
	task_tree_id
	task_id
	task_parent_id
	task_left_key
	task_right_key
	task_level
	task_open_time
	task_target_time
	task_close_time
	task_type
	task_type_id
	task_subtask_counter
	task_subtask_percents
	task_percents
	task_theme
	task_description
	task_close_reason

task_comments
	task_comment_id
	task_comment_task_id
	task_comment_comment_id
	task_comment_user_id
	task_comment_status
	task_comment_time
	task_comment_text

task_types
	task_type_id
	task_type_company_id
	task_type_status
	task_type_name

users
	id
	company_id
	status
	username
	firstname
	lastname
	midlename
	password
	telephone
	cellphone

users__log
	time
	loginuser_id
	user_id
	code
	message

workgroups
	id
	company_id
	status
	name

workgroups__log
	time
	loginuser_id
	company_id
	workgroup_id
	code
	message

works
	id
	company_id
	workgroup_id
	status
	name

works__log
	time
	loginuser_id
	company_id
	work_id
	code
	message






