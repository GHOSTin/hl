{% extends "ajax.tpl" %}
{% set number = component.number %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <li>
        <div>
        <a class="get_meters">Счетчики</a>
        </div>
    </li>
    <li>
        <ul style="padding:20px">
        {% for meter in component.meters %}
            <li class="meter" meter="{{ meter.id }}" serial="{{ meter.serial }}"><p class="get_meter_data">{{ meter.service }} {{ meter.name }} №{{ meter.serial }}</p></li>
        {% endfor %}
        </ul>
    </li>
{% endblock html %}