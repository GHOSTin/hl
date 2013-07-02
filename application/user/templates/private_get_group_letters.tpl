{% extends "ajax.tpl" %}
{% set letters = component.letters %}
{% block js %}
    $('.letters').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@user/build_group_letters.tpl' %}
{% endblock html %}