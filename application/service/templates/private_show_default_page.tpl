{% extends "default.tpl" %}
{% set services = component.services %}
{% block component %}
    <div>
        <ul class="nav nav-pills">
            <li><a class="get_dialog_create_service">Создать услугу</a></li>
        </ul>
    </div>
    {% include '@service/build_service_titles.tpl' %}
{% endblock component %}