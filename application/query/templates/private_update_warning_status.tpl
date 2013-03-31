{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'}%}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-general-warning_status').html(get_hidden_content());
{% endblock js %}
{% block html %}
	{% if query.warning_status in warning_statuses|keys %}
		{{warning_statuses[query.warning_status]}}
	{% endif %}
{% endblock html %}