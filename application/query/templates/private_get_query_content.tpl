{% extends "ajax.tpl" %}
	{% set query = component.query %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'}%}
	{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'}%}
	{% if query.get_initiator() == 'number' %}
				{% if component.numbers.numbers != false %}
					{% set number = component.numbers.numbers[component.numbers.structure[query.id].true[0]] %}
				{% endif %}
			{% endif %}
	{% block js %}
		$('.query[query_id = {{ query.get_id() }}]').html(get_hidden_content())
		.removeClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div  class="query-wrap
{% if query.get_status() in ['working','open', 'close', 'reopen']%}
	query_status_{{ query.get_status() }}
{% endif %}">
	<h4>
		{% if query.get_initiator() == 'number' %}
			<img src="/templates/default/images/icons/xfn-friend.png" />
		{% else %}
			<img src="/templates/default/images/icons/home-medium.png" />
		{% endif %}
		Заявка №{{ query.get_number() }} (
		{% if query.get_status() in statuses|keys %}
			{{ statuses[query.get_status()] }}
		{% else %}
			Статус не определен
		{% endif %}
		)
		<button class="close get_query_title">&times;</button>
	</h4>
	<ul class="nav nav-pills">
		<li><a href="#" class="get_documents">Документы</a></li>
	{% if query.get_status() in ['open', 'working'] %}
		<li><a href="#" class="get_dialog_close_query">Закрыть заявку</a></li>
	{% endif %}
	{% if query.get_status() == 'reopen' %}
		<li><a href="#" class="get_dialog_reclose_query">Перезакрыть заявку</a></li>
	{% endif %}
	{% if query.get_status() == 'open' %}
		<li><a href="#" class="get_dialog_to_working_query">Передать в работу</a></li>
	{% endif %}
	{% if query.get_status() == 'close' %}
		<li><a href="#" class="get_dialog_reopen_query">Переоткрыть</a></li>
	{% endif %}
	</ul>
	<ul class="query-general">
		<li>Время открытия: {{query.get_time_open()|date('H:i d.m.Y')}}</li>
		<li>Адрес: {{ query.get_street().get_name() }}, дом №{{ query.get_house().get_number() }}
			{% if query.get_initiator() == 'number' %}
				{% if component.numbers.numbers != false %}
					, кв. {{number.flat_number}}
					<li>Владелец: {{number.fio}}</li>
					<li>Лицевой счет: №{{number.number}}</li>
				{% endif %}
			{% endif %}
		</li>
		<li>Тип оплаты: <span class="query-general-payment_status">
			{% if query.get_payment_status() in payment_statuses|keys %}
				{{ payment_statuses[query.get_payment_status()] }}
			{% endif %}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_payment_status">изменить</span>
			{% endif %}
		</li>
		<li>Тип работ: <span class="query-general-work_type">{{ query.get_work_type().get_name() }}</span>  
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_work_type">изменить</span></li>
			{% endif %}
		<li>Тип заявки: 
			<span class="query-general-warning_status">
			{% if query.get_warning_status() in warning_statuses|keys %}
				{{ warning_statuses[query.get_warning_status()] }}
			{% endif %}
			</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_warning_status">изменить</span>
			{% endif %}
		</li>
		<li>
			Диспетчер: {{ query.get_creator().get_lastname() }} {{ query.get_creator().get_firstname() }} {{ query.get_creator().get_middlename() }}
		</li>
		<li>Описание: <span class="query-general-description">{{ query.get_description() }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_description">изменить</span>
			{% endif %}</li>
		{% if query.get_status() in ['close', 'reopen'] %}
			<li>Причина закрытия: <span class="query-general-reason">{{ query.get_close_reason() }}</span>
			{% if query.get_status() == 'reopen' %}
			<span class="cm get_dialog_edit_reason">изменить</span>
			{% endif %}</li>
		{% endif %}
		<lo>
			<div>Контактная информация
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_contact_information">изменить</span></div>
			{% endif %}
			<ul class="query-general-contacts">
				<li>ФИО: {{ query.get_contact_fio() }}</li>
				<li>Телефон: {{ query.get_contact_telephone() }}</li>
				<li>Сотовый: {{ query.get_contact_cellphone() }}</li>
			</ul>
		</li>
	</ul>
	<ul>
		<li class="query-numbers">
			<h5>Лицевые счета</h5>
		</li>
		<li class="query-users">
			<h5>Участники</h5>
		</li>
		<li class="query-works">
			<h5>Работы</h5>
		</li>
	</ul>
	</div>
	{% endblock html %}