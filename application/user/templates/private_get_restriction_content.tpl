{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profile_name = component.profile_name %}
{% set restriction_name = component.restriction_name %}
{% set items = component.items %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}] .profile[profile = "{{ profile_name }}"] .restriction[restriction = "{{ restriction_name }}"]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <ul class="restriction-content">   
        {% if restriction_name == 'departments' %}
            {% for department in items %}
                <li><input type="checkbox"> {{ department.name }}</li>
            {% endfor %}
        {% endif %}
    </ul>
{% endblock html %}