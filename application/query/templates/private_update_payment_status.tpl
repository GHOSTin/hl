{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'}%}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-general-payment_status').html(get_hidden_content());
{% endblock js %}
{% block html %}
	{% if query.payment_status in payment_statuses|keys %}
		{{payment_statuses[query.payment_status]}}
	{% endif %}
{% endblock html %}