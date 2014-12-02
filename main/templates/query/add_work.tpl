{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-works .works').html(get_hidden_content())
{% endblock js %}

{% block html %}
	{% include 'query/query_works.tpl'%}
{% endblock html %}