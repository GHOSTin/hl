{% extends "ajax.tpl" %}
{% set number = response.number %}
{% set enable_meters = response.enable_meters %}
{% set disable_meters = response.disable_meters %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .number-content-content').html(get_hidden_content());
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_add_meter">Привязать счетчик</a>
    </div>
    <div class="number-meters">
        {% include '@number/build_meters.tpl' %}
    </div>
{% endblock html %}