{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	{% block js %}
		$('.query[query_id = {{query.id}}] .query-works').append(get_hidden_content())
	{% endblock js %}
	{% block html %}
		<ul class="query-sub">
			{% if query.status in ['open', 'working', 'reopen'] %}
			<li class="get_dialog_add_work cm">добавить</li>
			{% endif %}
			<li>
				<ol class="works">
				{% include '@query/query_works.tpl' %}
				</ol>
			</li>
		</ul>
	{% endblock html %}
{% endif %}