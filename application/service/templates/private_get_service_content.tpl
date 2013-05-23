{% extends "ajax.tpl" %}
{% set service = component.services[0] %}
{% block js %}
    $('.service[service = {{ service.id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <div class="service_content">
        <ul class="nav nav-pills">
            <li><a>Переименовать</a></li>
        </ul>
    </div>
{% endblock html %}