{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set enable_meters = response.enable_meters %}
{% set disable_meters = response.disable_meters %}
<div class="panel panel-primary">
    <div class="panel-heading">Активные счетчики</div>
    <div class="panel-body">
        <ul>
        {% for meter in enable_meters %}
            <li class="meter" meter="{{ meter.get_id() }}" serial="{{ meter.get_serial() }}">
                {% set period = meter.get_period() %}
                <p class="get_meter_data">{{ services[meter.get_service()] }} {{ meter.get_name() }} №{{ meter.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y') }})</p>
            </li>
        {% else %}
            <li>Нет ни одного активного счетчика.</li>
        {% endfor %}
        </ul>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">Отключенные счетчики</div>
    <div class="panel-body">
        <ul>
        {% for meter in disable_meters %}
            <li class="meter" meter="{{ meter.get_id() }}" serial="{{ meter.get_serial() }}">
                {% set period = meter.get_period() %}
                <p class="get_meter_data">{{ services[meter.get_service()] }} {{ meter.get_name() }} №{{ meter.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y') }})</p>
            </li>
        {% else %}
            <li>Нет ни одного активного счетчика.</li>
        {% endfor %}
        </ul>
    </div>
</div>