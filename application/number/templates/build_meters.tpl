{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set enable_meters = component.enable_meters %}
{% set disable_meters = component.disable_meters %}
<h5>Активные счетчики</h5>
<ul>
{% for meter in enable_meters %}
    <li class="meter" meter="{{ meter.get_meter_id() }}" serial="{{ meter.get_serial() }}">
        {% set period = meter.get_period() %}
        <p class="get_meter_data">{{ services[meter.get_service()] }} {{ meter.get_name() }} №{{ meter.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y') }})</p>
    </li>
{% else %}
    <li>Нет ни одного активного счетчика.</li>
{% endfor %}
</ul>
<h5>Отключенные счетчики</h5>
<ul>
{% for meter in disable_meters %}
    <li class="meter" meter="{{ meter.get_meter_id() }}" serial="{{ meter.get_serial() }}">
        {% set period = meter.get_period() %}
        <p class="get_meter_data">{{ services[meter.get_service()] }} {{ meter.get_name() }} №{{ meter.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y') }})</p>
    </li>
{% else %}
    <li>Нет ни одного активного счетчика.</li>
{% endfor %}
</ul>