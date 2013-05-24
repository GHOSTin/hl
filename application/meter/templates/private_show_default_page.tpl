{% extends "default.tpl" %}
{% set meters = component.meters %}
{% block component %}
    <div>
        <ul class="nav nav-pills">
            <li><a class="get_dialog_create_meter">Создать счетчик</a></li>
        </ul>
    </div>
    {% include '@meter/build_meter_titles.tpl' %}
{% endblock component %}