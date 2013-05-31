{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.meter[meter = {{ meter.id }}] .meter-periods').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% for period in meter.periods %}
    <li>
        {% if period > 12 %}
            {{ period // 12 }} г {{ period % 12 }} месяц
        {% else %}
            {{ period }} мес.
        {% endif %}
    <a class="get_dialog_remove_period">удалить</a></li>
    {% endfor %}
{% endblock html %}