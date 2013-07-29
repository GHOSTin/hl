{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profiles = component.profiles %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <ul class="company-content unstyled">
        <li class="company-content-content">
            <ul>
                {% include '@user/build_profiles.tpl' %}
            </ul>
        </li>
    </ul>
{% endblock html %}