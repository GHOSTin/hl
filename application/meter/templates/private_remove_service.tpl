{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set services = component.services %}
{% block js %}
    $('.meter[meter = {{ meter.id }}] .meter-services').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% if services != false %}
        {% for service in services %}
            <li service="{{ service.id }}">{{ service.name }} <a class="get_dialog_remove_service">исключить</a></li>
        {% endfor %}
    {% else %}
        <li>Нет услуг</li>
    {% endif %}
{% endblock html %}