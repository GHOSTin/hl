{% extends "ajax.tpl" %}
{% set n2m = component.n2m %}
{% block js %}
    $('.number[number = {{ n2m.get_number().get_id() }}] .meter[serial = {{ n2m.get_serial() }}][meter = {{ n2m.get_meter().get_id() }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ n2m.get_number().get_id() }}] .meter[serial = {{ n2m.get_serial() }}][meter = {{ n2m.get_meter().get_id() }}] .get_meter_data').html('{{ services[n2m.get_service()] }} {{ n2m.get_meter().get_name() }} №{{ n2m.get_serial() }} ({{ n2m.get_date_next_checking()|date('d.m.Y')}})');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}