{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}]').find('.query-numbers .ibox-content').append(get_hidden_content())
{% endblock js %}

{% block html %}
<div class="query-numbers-menu m-t-xs">
    {% if query.get_status() in ['open', 'working', 'reopen'] and query.get_request() is empty %}
      <a class="btn btn-outline btn-default btn-w-m get_dialog_change_initiator">Изменить инициатора</a>
    {% endif %}
</div>
<div class="query-numbers-content">
	<ul class="list-group">
	{% for number in query.get_numbers() %}
		<li class="list-group-item" number="{{ number.get_id() }}">кв.{{ number.get_flat().get_number() }} {{ number.get_fio() }} (№{{ number.get_number() }})</li>
	{% else %}
		<li class="list-group-item">Нет лицевых счетов</li>
	{% endfor %}
	<ul>
</div>
{% endblock html %}