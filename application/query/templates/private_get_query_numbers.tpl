{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-numbers').append(get_hidden_content())
{% endblock js %}
{% block html %}
<div class="query-numbers-menu">
  <ul class="nav nav-pills">
    {% if query.get_status() in ['open', 'working', 'reopen'] %}
    <li><a class="get_dialog_change_initiator">Изменить инициатора</a></li>
    {% endif %}
  </ul>
</div>
<div class="query-numbers-content">
	<ul class="unstyle">
	{% for number in query.get_numbers() %}
		<li number="{{ number.get_id() }}">кв.{{ number.get_flat_number() }} {{ number.get_fio() }} (№{{ number.get_number() }})</li>
	{% else %}
		<li>Нет лицевых счетов</li>
	{% endfor %}
	<ul>
</div>
{% endblock html %}