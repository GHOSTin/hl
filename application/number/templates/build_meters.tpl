{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set enable_meters = component.enable_meters %}
{% set disable_meters = component.disable_meters %}

<div class="panel panel-primary">
    <h5 class="panel-heading">Активные счетчики</h5>
    <div class="panel-body">
        <ul>
            {% for meter in enable_meters %}
            <li class="meter" meter="{{ meter.meter_id }}" serial="{{ meter.serial }}">
                {% set period = meter.period %}
                <p class="get_meter_data">{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y') }})</p>
            </li>
            {% else %}
            <li>Нет ни одного активного счетчика.</li>
            {% endfor %}
        </ul>
    </div>
</div>
<div class="panel panel-info">
    <h5 class="panel-heading">Отключенные счетчики</h5>
    <div class="panel-body">
        <ul>
        {% for meter in disable_meters %}
            <li class="meter" meter="{{ meter.meter_id }}" serial="{{ meter.serial }}">
                {% set period = meter.period %}
                <p class="get_meter_data">{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y') }})</p>
            </li>
        {% else %}
            <li>Нет ни одного активного счетчика.</li>
        {% endfor %}
        </ul>
    </div>
</div>