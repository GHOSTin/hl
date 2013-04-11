{% extends "default.tpl" %}
{% set streets = component.streets %}
{% block component %}
     {% include '@number/build_street_titles.tpl' %}
{% endblock component %}