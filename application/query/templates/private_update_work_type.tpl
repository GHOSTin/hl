{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-work_type').html(get_hidden_content());
{% endblock js %}
{% block html %}
	{{ query.get_work_type_name() }}
{% endblock html %}