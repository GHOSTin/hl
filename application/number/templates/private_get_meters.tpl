{% extends "ajax.tpl" %}
{% set number = component.number %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content').html(get_hidden_content())
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
    <li>
        <ul style="padding:20px">
        {% for meter in component.meters %}
            <li class="meter" meter="{{ meter.id }}" serial="{{ meter.serial }}">
                <p class="get_meter_data">{{ meter.service }} {{ meter.name }} №{{ meter.serial }}</p>
            </li>
        {% endfor %}
        </ul>
    </li>
{% endblock html %}