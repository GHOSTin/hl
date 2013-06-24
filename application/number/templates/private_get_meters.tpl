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
        <h5>Активные счетчики</h5>
        <ul style="padding:20px" class="number-meters">
        {% for meter in component.enable_meters %}
            <li class="meter" meter="{{ meter.meter_id }}" serial="{{ meter.serial }}">
                {% set period = meter.period %}
                <p class="get_meter_data">{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y') }})</p>
            </li>
        {% else %}
            <li>Нет ни одного активного счетчика.</li>
        {% endfor %}
        </ul>
    </li>
    <li>
        <h5>Отключенные счетчики</h5>
        <ul style="padding:20px" class="number-meters">
        {% for meter in component.disable_meters %}
            <li class="meter" meter="{{ meter.meter_id }}" serial="{{ meter.serial }}">
                {% set period = meter.period %}
                <p class="get_meter_data">{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y') }})</p>
            </li>
        {% else %}
            <li>Нет ни одного активного счетчика.</li>
        {% endfor %}
        </ul>
    </li>
{% endblock html %}