{% extends "ajax.tpl" %}

{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'}%}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-payment_status').html(get_hidden_content());
{% endblock js %}

{% block html %}
	{% if query.get_payment_status() in payment_statuses|keys %}
		{{ payment_statuses[query.get_payment_status()] }}
	{% endif %}
{% endblock html %}