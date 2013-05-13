{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% block js %}
    $('.number[number = {{number.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
<ul class="number-content nav nav-tabs nav-stacked">
    {% include '@number/build_number_information.tpl' %}
</ul>
{% endblock html %}