{% extends "ajax.tpl" %}
{% set data = component.data %}
{% set centers = component.centers %}
{% block js %}
    $('.number[number = {{ data.number_id }}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{ data.number_id }}] .number-content-menu li').removeClass('active');
    $('.number[number = {{ data.number_id }}] .get_processing_centers').parent().addClass('active');
{% endblock js %}
{% block html %}
    <div><a class="get_dialog_add_processing_center">Добавить идентификатор</a></div>
    <ul>
        {% for center in centers %}
            <li center="{{ center.processing_center_id}}" identifier="{{ center.identifier}}">{{ center.processing_center_name }} ({{ center.identifier }}) <a class="get_dialog_exclude_processing_center">исключить</a></li>
        {% endfor %}
    </ul>
{% endblock html %}