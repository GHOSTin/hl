{% extends "ajax.tpl" %}
{% set data = component.data %}
{% set centers = component.centers %}
{% block js %}
    $('.number[number = {{data.number_id}}] .number-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <li>
        <ul class="nav nav-pills">
            <li>
                <a class="get_number_information">Информация о счете</a>
            </li>
            <li class="active"><a class="get_meters">Счетчики</a></li>
        </ul>
    </li>
    <div>
        {% for center in centers %}
            {{center.processing_center_name}}
        {% endfor %}
    </div>
{% endblock html %}