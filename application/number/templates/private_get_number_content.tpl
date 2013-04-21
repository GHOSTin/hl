{% extends "ajax.tpl" %}
{% block js %}
    $('.number[number = {{component.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
<ul class="number-content nav nav-tabs nav-stacked">
    {% include '@number/build_number_information.tpl' %}
</ul>
{% endblock html %}