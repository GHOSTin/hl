{% extends "ajax.tpl" %}

{% block js %}
    $('.queries').html(get_hidden_content('._queries'));
    $('.filter-content-select-house').html(get_hidden_content('._houses')).attr('disabled', false);
{% endblock js %}

{% block html %}
    <div class="_queries">
        <div class="muted">
            <small>
    	        Количество заявок: {{ queries|length }}
            </small>
        </div>
    	{% for query in queries %}
    		<div class="query get_query_content" query_id="{{ query.get_id() }}">
    			{% include 'query/build_query_title.tpl' %}
    		</div>
        {% else %}
             Нет заявок
    	{% endfor %}
    </div>
    <div class="_houses">
        {% include 'query/get_houses.tpl' %}
    </div>
{% endblock html %}