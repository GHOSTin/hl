{% if component.queries != false %}
    <div class="muted">
        <small>
	        Количество заявок: {{ component.queries.count() }}
        </small>
    </div>
	{% for query in component.queries.get_queries() %}
		<div class="query get_query_content" query_id="{{ query.get_id() }}">
			{% include '@query/build_query_title.tpl' %}
		</div>
	{% endfor %}
{% else %}
    Нет заявок
{% endif %}