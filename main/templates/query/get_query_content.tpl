{% extends "ajax.tpl" %}

{% set statuses = {'open':'Открытая', 'working':'В работе', 'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation':'Перерасчет'} %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned':'плановая'} %}
{% set creator = query.get_creator() %}

{% if query.get_initiator() == 'number' %}
	{% set number = query.get_numbers()[0] %}
{% endif %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}]').html(get_hidden_content()).removeClass('get_query_content');
{% endblock %}

{% block html %}
<div class="query-wrap query_status_{{ query.get_status() }}">
	<h4>
		{% if query.get_initiator() == 'number' %}
			<img src="/images/icons/xfn-friend.png" />
		{% else %}
			<img src="/images/icons/home-medium.png" />
		{% endif %}
		Заявка №{{ query.get_number() }} ({{ statuses[query.get_status()] }})
		<button class="close get_query_title">&times;</button>
	</h4>
	<ul class="nav nav-pills">
		<li>
			<a href="#" class="get_documents">Документы</a>
		</li>
	{% if query.get_status() in ['open', 'working'] %}
		<li>
			<a href="#" class="get_dialog_close_query">Закрыть заявку</a>
		</li>
	{% endif %}
	{% if query.get_status() == 'reopen' %}
		<li>
			<a href="#" class="get_dialog_reclose_query">Перезакрыть заявку</a>
		</li>
	{% endif %}
	{% if query.get_status() == 'open' %}
		<li>
			<a href="#" class="get_dialog_to_working_query">Передать в работу</a>
		</li>
	{% endif %}
	{% if query.get_status() == 'close' %}
		<li>
			<a href="#" class="get_dialog_reopen_query">Переоткрыть</a>
		</li>
	{% endif %}
	</ul>
	<ul class="query-general">
		<li>Время открытия: {{ query.get_time_open()|date('H:i d.m.Y') }}</li>
		<li>Адрес: {{ query.get_house().get_street().get_name() }}, дом №{{ query.get_house().get_number() }}{% if query.get_initiator() == 'number' %}, кв. {{ number.get_flat().get_number() }}{% endif %}
	{% if query.get_initiator() == 'number' %}
		<li>Владелец: {{ number.get_fio() }}</li>
		<li>Лицевой счет: №{{ number.get_number() }}</li>
	{% endif %}
		<li>Тип оплаты: <span class="query-general-payment_status">{{ payment_statuses[query.get_payment_status()] }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a href="#" class="get_dialog_edit_payment_status"> изменить</a>
			{% endif %}
		</li>
		<li>Тип работ: <span class="query-general-work_type">{{ query.get_work_type().get_name() }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a href="#" class="get_dialog_edit_work_type"> изменить</a></li>
			{% endif %}
		<li>Тип заявки:
			<span class="query-general-warning_status">{{ warning_statuses[query.get_warning_status()] }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a href="#" class="get_dialog_edit_warning_status"> изменить</a>
			{% endif %}
		</li>
		<li>
			Диспетчер: {{ creator.get_lastname() }} {{ creator.get_firstname() }} {{ creator.get_middlename() }}
		</li>
		<li>Описание: <span class="query-general-description">{{ query.get_description() }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a href="#" class="get_dialog_edit_description"> изменить</a>
			{% endif %}
		</li>
	{% if query.get_status() in ['close', 'reopen'] %}
		<li>Причина закрытия: <span class="query-general-reason">{{ query.get_close_reason() }}</span>
		{% if query.get_status() == 'reopen' %}
			<a href="#" class="get_dialog_edit_reason"> изменить</a>
		{% endif %}
		</li>
	{% endif %}
		<li>
			<div>Контактная информация
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
				<a href="#" class="get_dialog_edit_contact_information">изменить</a>
			{% endif %}
			</div>
			<ul class="query-general-contacts">
				<li>ФИО: {{ query.get_contact_fio() }}</li>
				<li>Телефон: {{ query.get_contact_telephone() }}</li>
				<li>Сотовый: {{ query.get_contact_cellphone() }}</li>
			</ul>
		</li>
	</ul>
	<ul>
		<li class="query-numbers">
			<h5>Лицевые счета <span class="label label-success">{{ query.get_numbers()|length }}</span></h5>
		</li>
		<li class="query-users">
			<h5>Участники <span class="label label-success">{{ query.get_users()|length }}</span></h5>
		</li>
		<li class="query-works">
			<h5>Работы <span class="label label-success">{{ query.get_works()|length }}</span></h5>
		</li>
		<li class="query-comments">
			<h5>Служебные комментарии <span class="label label-success">{{ query.get_comments()|length }}</span></h5>
		</li>
	</ul>
</div>
{% endblock html %}