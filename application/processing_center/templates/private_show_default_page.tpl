{% extends "default.tpl" %}
{% set centers = component.centers %}
{% block component %}
    <div>
        <a class="get_dialog_create_processing_center">Создать</a>
    </div>
    <ul class="unstyled processing-centers">
        {% include '@processing_center/build_centers.tpl' %}
    </ul>
{% endblock component %}