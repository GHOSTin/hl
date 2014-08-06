{% extends "ajax.tpl" %}
{% set street = response.street %}
{% block js %}
    $('.street[street = {{ street.get_id() }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@number/build_houses_titles.tpl' %}
{% endblock html %}