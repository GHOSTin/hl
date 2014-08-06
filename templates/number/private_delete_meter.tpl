{% extends "ajax.tpl" %}
{% set number = response.number %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .number-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_add_meter">Привязать счетчик</a>
    </div>
    <div class="number-meters">
        {% include '@number/build_meters.tpl' %}
    </div>
{% endblock html %}