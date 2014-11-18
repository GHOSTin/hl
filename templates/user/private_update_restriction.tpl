{% extends "ajax.tpl" %}
{% set user = response.user %}
{% set company = response.company %}
{% set profile_name = response.profile_name %}
{% set restriction_name = response.restriction_name %}
{% set item = response.item %}
{% set status = response.status %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}] .profile[profile = "{{ profile_name }}"] .restriction[restriction = "{{ restriction_name }}"] .item[item = {{ item }}] input').prop('checked', {% if status == true %}true{% else %}false{% endif %});
{% endblock js %}
{% block html %}
{% endblock html %}