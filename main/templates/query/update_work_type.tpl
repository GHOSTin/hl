{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-work_type').html(get_hidden_content());
{% endblock js %}

{% block html %}
	{{ query.get_work_type().get_name() }}
{% endblock html %}