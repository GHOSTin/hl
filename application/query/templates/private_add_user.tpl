{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-users .query-sub').html(get_hidden_content())
{% endblock js %}
{% block html %}
	{% include '@query/query_users.tpl'%}
{% endblock html %}