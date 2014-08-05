{% extends "ajax.tpl" %}
{% set query = component.query %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'}%}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-warning_status').html(get_hidden_content());
{% endblock js %}
{% block html %}
	{% if query.get_warning_status() in warning_statuses|keys %}
		{{warning_statuses[query.get_warning_status()]}}
	{% endif %}
{% endblock html %}