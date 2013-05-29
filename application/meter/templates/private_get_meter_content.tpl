{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.meter[meter = {{ meter.id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <div class="meter-content">
        <h3>Информация о счетчике</h3>
        <ul class="nav nav-pills">
            <li><a class="get_dialog_rename_meter">Переименовать</a></li>
        </ul>
        <div>Тарифность: <span class="meter-rates">{{ rates[meter.rates - 1] }}</span> <a class="get_dialog_edit_rates">изменить</a></div>
        <div>Разрядность: <span class="meter-capacity">{{ meter.capacity }} <a class="get_dialog_edit_capacity">изменить</a></div>
        <div style="margin-top:50px">
            <h3>Услуги</h3>
            <ul class="nav nav-pills">
                <li><a class="get_dialog_add_service">Добавить</a></li>
            </ul>
            <ul class="meter-services unstyled">
                {% for service in meter.service %}
                    <li service="{{ service }}">{{ services[service] }} <a class="get_dialog_remove_service">исключить</a></li>
                {% else %}
                    <li>Нет услуг</li>
                {% endfor %}
            </ul>
        </div>
    </div>
{% endblock html %}