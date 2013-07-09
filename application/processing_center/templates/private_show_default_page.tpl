{% extends "default.tpl" %}
{% set centers = component.centers %}
{% block component %}
    <div>
        <a>Создать</a>
    </div>
    <ul class="processing-centers">
        {% include '@processing_center/build_centers.tpl' %}
    </ul>
{% endblock component %}