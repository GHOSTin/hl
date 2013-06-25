{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
<h5>Активные счетчики</h5>
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
<h5>Отключенные счетчики</h5>
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