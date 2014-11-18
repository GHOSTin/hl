{% extends "ajax.tpl" %}
{% set query = response.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-comments .comments').html(get_hidden_content())
{% endblock js %}
{% block html %}
	{% include '@query/query_comments.tpl'%}
{% endblock html %}