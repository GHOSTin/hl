{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% block js %}
    $('.meter[meter = {{ meter.get_id() }}] .meter-services').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% for service in meter.get_services() %}
        <li service="{{ service }}">{{ services[service] }} <a class="get_dialog_remove_service">исключить</a></li>
    {% else %}
        <li>Нет услуг</li>
    {% endfor %}
{% endblock html %}