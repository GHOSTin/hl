{% extends "ajax.tpl" %}
{% set meter = response.meter %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.meter[meter = {{ meter.get_id() }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <div class="meter-content">
        <h3>Информация о счетчике</h3>
        <div>Тарифность: <span class="meter-rates">{{ rates[meter.get_rates() - 1] }}</span></div>
        <div>Разрядность: <span class="meter-capacity">{{ meter.get_capacity() }}</div>
        <div>Периоды поверки:
            <ul class="meter-periods">
                {% for period in meter.get_periods() %}
                <li period="{{ period }}">
                    {% if period > 12 %}
                        {{ period // 12 }} г {{ period % 12 }} месяц
                    {% else %}
                        {{ period }} мес.
                    {% endif %}
                </li>
                {% endfor %}
            </ul>
        </div>
        <div style="margin-top:50px">
            <h3>Услуги</h3>
            <ul class="meter-services unstyled">
                {% for service in meter.get_services() %}
                    <li service="{{ service }}">{{ services[service] }}</li>
                {% else %}
                    <li>Нет услуг</li>
                {% endfor %}
            </ul>
        </div>
    </div>
{% endblock html %}