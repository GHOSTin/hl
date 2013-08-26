{% extends "ajax.tpl" %}
{% set query = component.query %}
{% set numbers = query.get_numbers() %}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-numbers').append(get_hidden_content())
{% endblock js %}
{% block html %}
	<ul class="query-sub">
	{% for number in numbers %}
		<li number="{{number.id}}">кв.{{number.flat_number}} {{number.fio}} (№{{number.number}})</li>
	{% else %}
		<li>Нет лицевых счетов</li>
	{% endfor %}
	<ul>
{% endblock html %}