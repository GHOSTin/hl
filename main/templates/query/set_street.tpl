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

    	{% include 'query/query_titles.tpl' %}
    </div>
    <div class="_houses">
        {% include 'query/get_houses.tpl' %}
    </div>
{% endblock html %}