{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profile_name = component.profile_name %}
{% set restriction_name = component.restriction_name %}
{% set item = component.item %}
{% set status = component.status %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}] .profile[profile = "{{ profile_name }}"] .restriction[restriction = "{{ restriction_name }}"] .item[item = {{ item }}] input').prop('checked', {% if status == true %}true{% else %}false{% endif %});
{% endblock js %}
{% block html %}
{% endblock html %}