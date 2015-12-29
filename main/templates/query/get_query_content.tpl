{% extends "ajax.tpl" %}

{% set statuses = {'open':'Открытая', 'working':'В работе', 'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
{% set creator = query.get_creator() %}

{% if query.get_initiator() == 'number' %}
	{% set number = query.get_numbers()[0] %}
{% endif %}

{% block js %}
	$('.query[query_id ="{{ query.get_id() }}"]').html(get_hidden_content()).removeClass('get_query_content');
{% endblock %}

{% block html %}
<div class="query-wrap">
	<h4>
		{% if query.get_initiator() == 'number' %}
			<i class="fa fa-user notification-center-icon" style="color:#AADDAF; font-size:12px" alt="Заявка на личевой счет"></i>
		{% else %}
			<i class="fa fa-home notification-center-icon" style="color:#C8D5C8; font-size:12px" alt="Заявка на дом"></i>
		{% endif %}
    {% if query.get_request %}
     <i class="fa fa-eye-open notification-center-icon" style="font-size:12px" alt="Заявка из личного кабинета"></i>
    {% endif %}
		Заявка №{{ query.get_number() }} ({{ statuses[query.get_status()] }})
		<button class="close get_query_title">&times;</button>
	</h4>
  <div class="m-b">
    <a class="btn btn-outline btn-default btn-w-m get_documents">Документы</a>
  {% if query.get_status() in ['open', 'working'] %}
    <a class="btn btn-outline btn-default btn-w-m get_dialog_close_query">Закрыть заявку</a>
  {% endif %}
  {% if query.get_status() == 'reopen' %}
    <a class="btn btn-outline btn-default btn-w-m get_dialog_reclose_query">Перезакрыть заявку</a>
  {% endif %}
  {% if query.get_status() == 'open' %}
    <a class="btn btn-outline btn-default btn-w-m get_dialog_to_working_query">Передать в работу</a>
  {% endif %}
  {% if query.get_status() == 'close' %}
    <a class="btn btn-outline btn-default btn-w-m get_dialog_reopen_query">Переоткрыть</a>
  {% endif %}
    <a class="btn btn-outline btn-default btn-w-m" href="/queries/{{ query.get_id() }}/history/" target="_blank">История</a>
  </div>
	<dl class="query-general dl-horizontal m-b-xs">
		<dt>Время открытия:</dt><dd> {{ query.get_time_open()|date('d.m.Y H:i') }}</dd>
    {% if query.get_status() in ['close', 'reopen'] %}
    <dt>Время закрытия: </dt><dd> {{ query.get_time_close()|date('d.m.Y H:i') }}</dd>
    {% endif %}
		<dt>Адрес:</dt><dd> {{ query.get_house().get_street().get_name() }}, дом №{{ query.get_house().get_number() }}{% if query.get_initiator() == 'number' %}, кв. {{ number.get_flat().get_number() }}{% endif %}</dd>
	{% if query.get_initiator() == 'number' %}
		<dt>Владелец:</dt><dd> {{ number.get_fio() }}</dd>
		<dt>Лицевой счет:</dt><dd> №{{ number.get_number() }}</dd>
    <dt>Задолженость:</dt><dd> <strong>{{ number.get_debt()|number_format(2, '.', ' ') }} руб.</strong></dd>
	{% endif %}
		<dt>Тип работ:</dt><dd> <span class="query-general-work_type">{{ query.get_work_type().get_name() }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a class="text-navy get_dialog_edit_work_type"> изменить</a></dd>
			{% endif %}
		<dt>Тип заявки:</dt><dd>
			<span class="query-general-query_type">{{ query.get_query_type().get_name() }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a class="text-navy get_dialog_change_query_type"> изменить</a>
			{% endif %}
		</dd>
    <dt>Видимость:</dt><dd>
      <span class="query-general">{% if query.is_visible() %}<i class="fa fa-eye notification-center-icon" style="font-size:12px" alt="Заявка из личного кабинета"></i> Видима{% else %}Скрыта{% endif %}</span>
      {% if query.get_status() in ['open', 'working', 'reopen'] %}
      <a class="get_dialog_edit_visible text-navy"> изменить</a></li>
      {% endif %}
    </dd>
		<dt>Диспетчер: </dt><dd>{{ creator.get_lastname() }} {{ creator.get_firstname() }} {{ creator.get_middlename() }}</dd>
		<dt>Описание:</dt><dd> <span class="query-general-description">{{ query.get_description() }}</span>
			{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a class="text-navy get_dialog_edit_description"> изменить</a>
			{% endif %}
		</dd>
	{% if query.get_status() in ['close', 'reopen'] %}
		<dt>Причина закрытия:</dt><dd> <span class="query-general-reason">{{ query.get_close_reason() }}</span>
		{% if query.get_status() == 'reopen' %}
			<a class="text-navy get_dialog_edit_reason"> изменить</a>
		{% endif %}
		</dd>
	{% endif %}
	</dl>
  <div class="row">
    <div class="col-md-6">
      <div>
        {% if query.get_status() in ['open', 'working', 'reopen'] %}
          <a class="pull-right text-navy get_dialog_edit_contact_information">изменить</a>
        {% endif %}
        <h3>Контактная информация</h3>
      </div>
      <dl class="query-general-contacts dl-horizontal">
        <dt>ФИО:</dt><dd> {{ query.get_contact_fio() }}</dd>
	      {% set start = query.get_contact_telephone()|length - 4 %}
        <dt>Телефон:</dt>
	      <dd>
		      {% if start > 0 %}
			      {{ query.get_contact_telephone()|slice(0, start) }}-{{ query.get_contact_telephone()|slice(start,2) }}-{{ query.get_contact_telephone()|slice(start+2,2) }}
		      {% else %}
			      {{ query.get_contact_telephone() }}
		      {% endif %}
	      </dd>
        <dt>Сотовый:</dt>
	      <dd>
				{% if query.get_contact_cellphone() > 0 %}
		      ({{ query.get_contact_cellphone()|slice(0,3) }}) {{ query.get_contact_cellphone()|slice(3,3) }}-{{ query.get_contact_cellphone()|slice(6,2) }}-{{ query.get_contact_cellphone()|slice(8,2) }}
				{% else %}
					{{ query.get_contact_cellphone() }}
				{% endif %}
	      </dd>
      </dl>
    </div>
    {% if query.get_initiator() == 'number' and number.get_events() is not empty %}
      <div class="col-md-6">
        <h3>Последние события</h3>
        <dl class="dl-horizontal">
          {% for event in number.get_events()|slice(0,5) %}
            <dt>{{ event.get_time()|date("d.m.Y") }}</dt><dd>{{ event.get_name() }}</dd>
          {% endfor %}
        </dl>
      </div>
    {% endif %}
  </div>
  <div class="ibox collapsed m-b-sm query-numbers">
    <div class="ibox-title">
      <h5>Лицевые счета</h5>
      <div class="ibox-tools">
        <span class="label label-success">{{ query.get_numbers().count() }}</span>
        <a class="collapse-link">
          <i class="fa fa-chevron-up"></i>
        </a>
      </div>
    </div>
    <div class="ibox-content no-padding"></div>
  </div>
  <div class="ibox collapsed m-b-sm query-users">
    <div class="ibox-title">
      <h5>Участники</h5>
      <div class="ibox-tools">
        <span class="label label-success">{{ query.get_users().count() }}</span>
        <a class="collapse-link">
          <i class="fa fa-chevron-up"></i>
        </a>
      </div>
    </div>
    <div class="ibox-content no-padding"></div>
  </div>
  <div class="ibox collapsed m-b-sm query-works">
    <div class="ibox-title">
      <h5>Работы</h5>
      <div class="ibox-tools">
        <span class="label label-success">{{ query.get_works().count() }}</span>
        <a class="collapse-link">
          <i class="fa fa-chevron-up"></i>
        </a>
      </div>
    </div>
    <div class="ibox-content no-padding"></div>
  </div>
  <div class="ibox collapsed m-b-sm query-files">
    <div class="ibox-title">
      <h5>Файлы</h5>
      <div class="ibox-tools">
        <span class="label label-success">{{ query.get_files().count() }}</span>
        <a class="collapse-link">
          <i class="fa fa-chevron-up"></i>
        </a>
      </div>
    </div>
    <div class="ibox-content no-padding"></div>
  </div>
  <div class="ibox collapsed m-b-sm query-comments">
    <div class="ibox-title">
      <h5>Служебные комментарии</h5>
      <div class="ibox-tools">
        <span class="label label-success">{{ query.get_comments().count() }}</span>
        <a class="collapse-link">
          <i class="fa fa-chevron-up"></i>
        </a>
      </div>
    </div>
    <div class="ibox-content no-padding"></div>
  </div>
</div>
{% endblock html %}