{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
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
    <div>
        <a class="get_dialog_add_meter">Привязать счетчик</a>
    </div>
    <li>
        <ul style="padding:20px" class="number-meters">
        {% for meter in component.meters %}
            <li class="meter" meter="{{ meter.meter_id }}" serial="{{ meter.serial }}">
                {% set period = meter.period %}
                <p class="get_meter_data">{{ services[meter.service[0]] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y') }})</p>
            </li>
        {% else %}
            <li>Ни одного счетчика еще не привязано.</li>
        {% endfor %}
        </ul>
    </li>
{% endblock html %}