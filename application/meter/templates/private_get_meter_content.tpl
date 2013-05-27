{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% block js %}
    $('.meter[meter = {{ meter.id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <div class="meter-content">
        <h3>Информация о счетчике</h3>
        <ul class="nav nav-pills">
            <li><a class="get_dialog_rename_meter">Переименовать</a></li>
        </ul>
        <div>Разрядность: {{ meter.capacity }}</div>
        <div>Тарифность: {{ meter.rates }}</div>
        <div style="margin-top:50px">
            <h3>Услуги</h3>
            <ul class="nav nav-pills">
                <li><a class="get_dialog_add_service">Добавить</a></li>
            </ul>
        </div>
    </div>
{% endblock html %}