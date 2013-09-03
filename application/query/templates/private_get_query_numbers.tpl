{% extends "ajax.tpl" %}
{% set query = component.query %}
{% set numbers = query.get_numbers() %}
{% block js %}
	$('.query[query_id = {{ query.id }}] .query-numbers').append(get_hidden_content())
{% endblock js %}
{% block html %}
<div class="query-numbers-menu">
  <ul class="nav nav-pills">
    <li><a class="get_dialog_change_initiator">Изменить инициатора</a></li>
  </ul>
</div>
<div class="query-numbers-content">
	<ul class="unstyle">
	{% for number in numbers %}
		<li number="{{number.id}}">кв.{{number.flat_number}} {{number.fio}} (№{{number.number}})</li>
	{% else %}
		<li>Нет лицевых счетов</li>
	{% endfor %}
	<ul>
</div>
{% endblock html %}